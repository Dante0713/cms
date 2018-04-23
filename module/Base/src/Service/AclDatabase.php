<?php
/**
 * File for Acl Class
 *
 * @category  User
 * @package   User_Acl
 * @author    Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */


namespace Base\Service;


use Base\Entity\RecursiveResourceIterator;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Permissions\Acl\Role\GenericRole as Role;

/**
 * Class to handle Acl
 *
 * This class is for loading ACL defined in a config
 *
 * @category User
 * @package User_Acl
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license http://binware.org/license/index/type:new-bsd New BSD License
 */
class AclDatabase extends ZendAcl
{

    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'guest';

    /**
     * Constructor
     * @param $sm \Zend\ServiceManager\ServiceManager
     * @param array $config
     * @throws \Exception
     */
    public function __construct($sm, $config)
    {

        if (!isset($config['roles']) || !isset($config['resources'])) {
            throw new \Exception('Invalid ACL Config found');
        }
        $roles = $config['roles'];
        if (!isset($roles[self::DEFAULT_ROLE])) {
            $roles[self::DEFAULT_ROLE] = '';
        }

        $this->_addRoles($roles)->_addResources($config['resources']);
        // TODO cache
        // get All Resources
        /** @var  $em \Doctrine\ORM\EntityManager */
        $em = $sm->get('doctrine.entitymanager.orm_default');
        $rootResource = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Resource', 'u')
            ->where('u.resource IS NULL')
            ->andWhere('u.kind=:kind')
            ->andWhere('u.is_active=1')
            ->setParameter('kind', 'menu')
            ->getQuery()
            ->getResult();

        $collection = new ArrayCollection($rootResource);
        $category_iterator = new RecursiveResourceIterator($collection);
        $recursive_iterator = new \RecursiveIteratorIterator($category_iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $this->_setResources($recursive_iterator);

        // get All Roles
        $roles = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Role', 'u')
            ->where('u.kind <>:u_kind')
            ->orderBy('u.deep', 'asc')
            ->setParameter('u_kind', 'system')
            ->getQuery()
            ->getResult();

        // foreach ( $roles as $row ) {
        // echo $row->getId().'--'.$row->getKind().'-'.$row->getName().'<BR>';
        // }
        /** @var  $row \Base\Entity\Role */
        foreach ($roles as $row) {
            // if ($this->hasRole($row->getName()))
            // continue;

            if ($row->getRoleInheritsRelatedByRoleParentId()->count()) {
                $arr = array();
                foreach ($row->getRoleInheritsRelatedByRoleParentId() as $row2) {
                    $arr[] = $row2->getRoleRelatedByRoleParentId()->getKind() . '-' . $row2->getRoleRelatedByRoleParentId()->getName();
                }
                $this->addRole($row->getKind() . '-' . $row->getName(), $arr);
            } else {
                // echo $row->getKind().'-'.$row->getName().'<BR>';
                if ($row->getKind() == 'system')
                    $this->addRole($row->getKind() . '-' . $row->getName());
                else
                    $this->addRole($row->getKind() . '-' . $row->getName(), array(
                        'system-註冊會員'
                    ));
            }
        }
        // print_r($this->getRoles()); exit;
        $privilegeRes = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Privilege', 'u')
            ->leftJoin('u.resource', 'r')
            ->where('r.is_active=1')
            ->getQuery()
            ->getResult();


        /** @var  $row \Base\Entity\Privilege */
        foreach ($privilegeRes as $row) {
            $controllers = $row->getController();
            if ($controllers !== NULL) {
                $arr = explode(',', $controllers);
                // 加入 index controller 做為每個 module 的入口資源
                $arr[] = 'index';
                foreach ($arr as $controller) {
                    $resourceRow = $row->getResource()->getResourceName() . ":" . $controller;
                    if (!$this->hasResource($resourceRow))
                        $this->addResource($resourceRow);

                    $this->allow($row->getRole()
                            ->getKind() . '-' . $row->getRole()
                            ->getname(), $resourceRow);
                }
            } else {
                $this->allow($row->getRole()
                        ->getKind() . '-' . $row->getRole()
                        ->getname(), $row->getResource()
                    ->getResourceName());
            }
        }

        // print_r($this->getRoles());
    }

    protected function _setResources($resource)
    {

        /** @var  $row \Base\Entity\Resource */
        foreach ($resource as $row)

            if (isset($row) and $row->getResource()) {
                if (!$row->getIsActive() and $row->getKind() == 'module') continue;
                // if (!$this->hasResource($row->getResourceName()))
                $this->addResource($row->getResourceName(), $row->getResource()
                    ->getResourceName());
                $this->_setResources($row->getResource());
            } else
                $this->addResource($row->getResourceName());
    }

    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     * @return \Base\Acl\Acl
     */
    protected function _addRoles($roles)
    {
        foreach ($roles as $name => $parent) {
            if (!$this->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }

                $this->addRole(new Role($name), $parent);
            }
        }

        return $this;
    }

    /**
     * Adds Resources to ACL
     *
     * @param
     *            $resources
     * @return \Base\Acl\Acl
     * @throws \Exception
     */
    protected function _addResources($resources)
    {
        foreach ($resources as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller == 'all') {
                    $controller = null;
                } else {
                    $tempArr = explode(":", $controller);
                    // 加入 index controller 做為每個 module 的入口資源
                    $tempArr[] = 'index';
                    $parent = null;
                    foreach ($tempArr as $resource) {
                        if ($parent)
                            $resource = $parent . ':' . $resource;
                        if (!$this->hasResource($resource)) {
                            $this->addResource(new Resource($resource), $parent);
                        }
                        $parent = $resource;
                    }
                }

                foreach ($actions as $role => $action) {
                    if ($action == 'all') {
                        $action = null;
                    }

                    if ($permission == 'allow') {
                        $this->allow($role, $controller, $action);
                    } elseif ($permission == 'deny') {
                        $this->deny($role, $controller, $action);
                    } else {
                        throw new \Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }

        return $this;
    }
}
