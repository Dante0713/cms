<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/4/18
 * Time: 下午 05:30
 */

namespace Application\Controller;

use Application\Form\ArticleForm;
use Base\Controller\BaseController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\Paginator\Paginator;

class ForumController extends BaseController
{
    public function indexAction()
    {
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
            $qb->where('u.article_title like :keyword')
                ->setParameter('keyword', '%'.$queryKeyword.'%');
            $keyword = $queryKeyword;
            $Page = 1;
        }elseif($routeKeyword){
            //舊有搜尋字
            $qb->where('u.article_title like :keyword')
                ->setParameter('keyword', '%'.$routeKeyword.'%');
            $keyword = $routeKeyword;
            $Page = $this->params()->fromRoute('page');
        }

//        for ($i=0;$i<sizeof($qb->getQuery()->getArrayResult());$i++){
//            $qb->getQuery()->setParameter('content',$qb->getQuery()->getArrayResult()[$i]['content'] = substr($qb->getQuery()->getArrayResult()[$i]['content'],0,20));
//            var_dump($qb->getQuery()->getArrayResult());
//        }

//        foreach ($qb->getQuery()->getArrayResult() as $articleEntity){
//            $qb
//            var_dump($articleEntity['content']);
//        }


        $adapter = new DoctrineAdapter(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($Page);

        $paginator->setPageRange(5);
//        var_dump($paginator->setItem(0));exit;

        $viewModel->setVariable('keyword', $keyword);
        $viewModel->setVariable('paginator', $paginator);
        return $viewModel;
    }

    public function editAction()
    {
        $viewModel = new ViewModel();
        $form = new ArticleForm();
        $em = $this->getEntityManager();
//        $menuArr = $em->get('Base\Entity\Forum');
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);

            if ($form->isValid()) {
                $data = $form->getData();
            }
        }

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

    public function healthAction()
    {
        $em = $this->getEntityManager();
        $viewModel = new ViewModel();
        $userid = $this->getAuthService()->getIdentity()->getId();
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\Article', 'u')
            ->where('u.user_id = :userid')
            ->setParameter('userid',$userid);
        $keyword = $this->params()->fromQuery('keyword',null);
        $Page =  $this->params()->fromRoute('page');

        $queryKeyword = $this->params()->fromQuery('keyword',null);
        $routeKeyword = $this->params()->fromRoute('keyword',null);
        if ($queryKeyword){
            //新輸入搜尋字
            $qb->where('u.chinese_name like :keyword')
                ->setParameter('keyword', '%'.$queryKeyword.'%');
            $keyword = $queryKeyword;
            $Page = 1;
        }elseif($routeKeyword){
            //舊有搜尋字
            $qb->where('u.chinese_name like :keyword')
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
    }
    public function ownArticlesAction()
    {

    }
}