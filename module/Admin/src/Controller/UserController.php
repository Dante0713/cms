<?php

namespace Admin\Controller;


use Admin\Form\UserForm;
use Admin\Form\UploadForm;
use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Paginator\Paginator;

class UserController extends BaseController
{

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
        $viewModel = new ViewModel();

        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User', 'u')
            ->orderBy('u.id');
        $keyword = $this->params()->fromQuery('keyword',null);
        $page =  $this->params()->fromRoute('page');
        $queryKeyword = $this->params()->fromQuery('keyword',null);
        $routeKeyword = $this->params()->fromRoute('keyword',null);
        if ($queryKeyword){
            //新輸入搜尋字
            $qb->where('u.chinese_name like :keyword')
                ->setParameter('keyword', '%'.$queryKeyword.'%');
            $keyword = $queryKeyword;
            $page = 1;
        }elseif($routeKeyword){
            //舊有搜尋字
            $qb->where('u.chinese_name like :keyword')
                ->setParameter('keyword', '%'.$routeKeyword.'%');
            $keyword = $routeKeyword;
            $page = $this->params()->fromRoute('page');
        }

        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
        $paginator->setPageRange(5);

        $viewModel->setVariable('keyword', $keyword);
        $viewModel->setVariable('paginator', $paginator);
        $viewModel->setVariable('roleArr', $roleArr);

        return $viewModel;

    }

    public function editAction()
    {
        $form = new UserForm();
//        $em = $this->getEntityManager();
//        $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
//        $form->get('role')->setValueOptions($roleArr);


        if ($id = $this->params()->fromQuery('id')) {
            $em = $this->getEntityManager();
            $res = $em->getRepository('Base\Entity\User')->find($id);
            $form->bind($res);
        }

        $viewModel = new ViewModel();

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function saveAction()
    {
        $jsonModel = new JsonModel();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $em = $this->getEntityManager();
            if (!$userRes = $em->getRepository('Base\Entity\User')->find($data['id']))
                $userRes = new \Base\Entity\User();

            $form = new UserForm();
//            $roleArr = $em->getRepository('Base\Entity\User')->getRoleArray();
//            $form->get('role')->setValueOptions($roleArr);

            $form->setData($data);
            if ($form->isValid()) {
                $userRes->setNickname($data['display_name']);
                $userRes->setUsername($data['username']);
                $userRes->setType('A');
              //  $userRes->setRole($data['role']);
                $userRes->setPassword(\Zend\Ldap\Attribute::createPassword($data['password']));
                $em->persist($userRes);
                $em->flush();
                $jsonModel->setVariable('success', true);
            } else {
                $jsonModel->setVariable('success', false);
                $jsonModel->setVariable('message', $form->getMessages());
            }
        }

        return $jsonModel;
    }

    public function deleteAction()
    {
        $id = $this->params()->fromQuery('id');
        $em = $this->getEntityManager();
        $jsonModel = new JsonModel();
        /** @var  $res \Base\Entity\Competition */
        $res = $em->getRepository('Base\Entity\User')->find($id);

        $em->remove($res);
        $em->flush();
        $jsonModel->setVariable('success', true);


        return $jsonModel;
    }

    public function uploadAction()
    {
        $form = new UploadForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the $_FILES info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();

                // Form is valid, save the form!
                return $this->redirect()->toRoute('admin/upload-success');
            }
        }

        return ['form' => $form];
    }
    public function uploadSuccessAction()
    {
        return new ViewModel();
    }
}