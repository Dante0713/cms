<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Form\ContactForm;
use Base\Controller\BaseController;
use Base\Service\Factory\CacheApcFactory;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Paginator\Paginator;

class IndexController extends BaseController
{
    public function indexAction()
    {
//        $viewModel = new ViewModel();
//        $form = new ContactForm();
//        if ($this->getRequest()->isPost()) {
//            $data = $this->params()->fromPost();
//            $form->setData($data);
//
//            if ($form->isValid()) {
//                $data = $form->getData();
//            }
//        }
//
//        $viewModel->setVariable('form', $form);

        $em = $this->getEntityManager();
        $viewModel = new ViewModel();
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Article', 'u')
            ->orderBy('u.article_id', 'DESC');
        $keyword = $this->params()->fromQuery('keyword',null);
        $Page =  $this->params()->fromRoute('page');

        $queryKeyword = $this->params()->fromQuery('keyword',null);
        $routeKeyword = $this->params()->fromRoute('keyword',null);
        if ($queryKeyword){
            //新輸入搜尋字
            $qb->where('u.article_title like :keyword or u.content like :keyword')
                ->setParameter('keyword', '%'.$queryKeyword.'%');
            $keyword = $queryKeyword;
            $Page = 1;
        }elseif($routeKeyword){
            //舊有搜尋字
            $qb->where('u.article_title like :keyword or u.content like :keyword')
                ->setParameter('keyword', '%'.$routeKeyword.'%');
            $keyword = $routeKeyword;
            $Page = $this->params()->fromRoute('page');
        }


        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($Page);

        $paginator->setPageRange(5);

        $viewModel->setVariable('keyword', $keyword);
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
    }

//    public function md5Action()
//    {
//        $em = $this->getEntityManager();
//        $conn = $em->getConnection();
//
//        $sql = 'SELECT * FROM user';
//        $res = $conn->fetchAll($sql);
////        $conn->executeUpdate('UPDATE user SET status = ? ', [ 1 ]);
//        foreach( $res as $data)
//        {
//            echo $data['id'].'-'.  $data['username'] .'-'. $data['password'].'-'.$data['status'].'<BR>';
//            $conn->executeUpdate('UPDATE user SET password = ? WHERE id = ?', [
//                md5($data['password']),
//                $data['id']
//            ]);
//        }
//
//        return new ViewModel();
//    }


    public function getUserAction()
    {
        $id = (int) $this->params()->fromQuery('user_id');

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User', 'u')
            ->where('u.id=:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();

        $viewModel = new ViewModel();

        $viewModel->setVariable('data', $qb[0]);

        return $viewModel;
    }


    public function listUserAction()
    {
        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User', 'u')
            ->orderBy('u.chinese_name');

        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(20);

        if ($page = $this->params()->fromQuery('page'))
            $paginator->setCurrentPageNumber($page);

        $viewModel = new ViewModel();
        $viewModel->setVariable('paginator', $paginator);

        return $viewModel;
    }

}
