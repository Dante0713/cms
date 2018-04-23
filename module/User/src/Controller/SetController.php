<?php
namespace User\Controller;
use Base\Controller\BaseController;
use User\Form\UserdataForm;
use User\Form\UserForm;
use User\Form\RegisterForm as RegisterForm;
use Zend\InputFilter\InputFilter;
use User\Form\AccountFIlter;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;


class SetController extends BaseController {

    protected $form;
    protected $storage;
    protected $authservice;





    public function getSessionStorage() {
        if (!$this->storage) {
            $this->storage = $this->getServiceLocator()
            ->get('Base\Model\MyAuthStorage');
        }

        return $this->storage;
    }

    public function indexAction() {        
        $viewModel = new ViewModel();

        /** @var  $user \Base\Entity\User */
        $user = $this->getAuthService()->getIdentity();

        if ($user === NULL)            
          return  $this->redirect()->toRoute('home');
        
        $em = $this->getEntityManager();


        $form = new UserdataForm();
        $current_user = $this->getAuthService()->getIdentity()->getUsername();


        /** @var $qb \Base\Entity\User*/
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\User','u')
            ->where('u.username = :user')
            ->setParameter('user', $current_user)
            ->getQuery()
            ->getOneOrNullResult();

        if ($qb) {
            $form->get('chinese_name')->setValue($qb->getChineseName());
            $form->get('english_name')->setValue($qb->getEnglishName());
            $form->get('nick_name')->setValue($qb->getNickname());
            $form->get('email')->setValue($qb->getEmail());
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                // 預設值填入
                $data = $form->getData();
                $userRepo = $qb;
                $userRepo->setChineseName($data['chinese_name']);
                $userRepo->setEnglishName($data['english_name']);
                $userRepo->setNickname($data['nick_name']);
                $userRepo->setEmail($data['email']);

                $em->persist($userRepo);
                $em->flush();
                $this->flashMessenger()->addSuccessMessage('更新成功！');

                return $this->redirect()->refresh();
            }

        }

        $viewModel->setVariable('form', $form);

        $viewModel->setVariable('username', $qb->getUsername());

        $type = $qb->getType();
        if($type){
            if($type === 'A')
            $type = '親職互動平台';
        }else
            $type='';


        $viewModel->setVariable('type', $type);
        return $viewModel;
    }

    public function userStateAction() {
        return new ViewModel();
    }

    public function generateAction() {
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', "image/png");

        $id = $this->params('id', false);

        if ($id) {

            $image = './data/captcha/' . $id;

            if (file_exists($image) !== false) {
                $imagegetcontent = @file_get_contents($image);

                $response->setStatusCode(200);
                $response->setContent($imagegetcontent);

                if (file_exists($image) == true) {
                    unlink($image);
                }
            }

        }

        return $response;
    }





    public function changePasswordAction()
    {
        $viewModel = new ViewModel();
        $username = $this->getAuthService()->getIdentity()->getUsername();
        $registerForm = new RegisterForm();
        $form = new \Zend\Form\Form('changePasswordForm');
        $form->add($registerForm->get('password'));
        $form->add($registerForm->get('re_password'));
      //  $form->add($registerForm->get('submit')->setAttribute('value', '修改密碼'));
        $accountFilter = new AccountFIlter();
        $filter = new InputFilter();
        $filter->add($accountFilter->get('password'))
        ->add($accountFilter->get('re_password'));
        $form->setInputFilter($filter);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->getEntityManager();
                $user = $em->getRepository('Base\Entity\User')
                ->findOneBy(array('username'=>$username));
                if ($user) {
                    //$user->setPassword(md5($form->get('password')->getValue()));
                    $user->setPassword(md5($form->get('password')->getValue()));

                    $em->persist($user);
                    $em->flush();

                    $this->getServiceManager()->get('Log')->info($username.' changed password');
                    $this->flashMessenger()->addSuccessMessage('更改密碼成功!');
                    return $this->redirect()->refresh();

                }
            }
        }

        $viewModel->setVariable('form', $form);
        return $viewModel;
    }

    public function uploadAvatarAction()
    {
        $viewModel = new ViewModel();

        return $viewModel;
    }

    public function cropAction()
    {
        $jsonModel = new JsonModel();
        $gd = new \User\Model\GdImage();

        foreach($_POST['imgcrop'] as $k => $v) {

            $targetPath = './data/tmp/';
            $targetFile =  str_replace('//','/',$targetPath) . $v['filename'];

            $gd->crop($targetFile, $v['x'], $v['y'], $v['w'], $v['h']);

            $fileName = $targetPath.$v['filename'];

            $type = end(explode('.',$fileName));
            $dm = $this->getDocumentManager();
            $id =  $user = $this->getAuthService()->getIdentity()->getUsername();
            $kindId = 'user-avatar-' . $id;

            if ($image = $dm->getRepository('Base\Document\Image')->findOneBy(array('kindId' => $kindId))) {
                $image->setFile($fileName);
                $dm->persist($image);
                $dm->flush();
                unlink($fileName);
            }
        }
        return $jsonModel;
    }







}
