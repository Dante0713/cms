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
use Base\Entity\Article;
use Zend\View\Model\JsonModel;
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
            $qb->where('u.article_title like :keyword OR u.content like :keyword')
                ->setParameter('keyword', '%'.$queryKeyword.'%');
//            var_dump($qb->where('u.content like :keyword')
//                ->setParameter('keyword', '%'.$queryKeyword.'%'));exit;
            $keyword = $queryKeyword;
            $Page = 1;
        }elseif($routeKeyword){
            //舊有搜尋字
            $qb->where('u.article_title like :keyword OR u.content like :keyword')
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

    public function editAction()
    {
        $viewModel = new ViewModel();
        $form = new ArticleForm();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
        }

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function saveAction()
    {
        $jsonModel = new JsonModel();
        $em = $this->getEntityManager();
        $form = new ArticleForm();
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $articleRes = new Article();
            $form->bind($articleRes);
            $form->setData($data);
            if ($form->isValid()) {
                /** @var  $user \Base\Entity\User */
                $user = $this->getAuthService()->getIdentity();
                $articleRes->setArticleTitle($data['article_title']);
                $articleRes->setContent($data['content']);
                $articleRes->setUploadTime(new \DateTime());
                $articleRes->setUser($user);

                $em->persist($articleRes);
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