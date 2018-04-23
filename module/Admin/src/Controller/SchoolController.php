<?php
/**
 *
 *
 * @author    sfs teams <zfsfs.team@gmail.com>
 * @copyright 2010-2018 (http://www.sfs.tw)
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      http://www.sfs.tw
 * Date: 2018/3/15
 * Time: 下午 1:34
 */

namespace Admin\Controller;


use Admin\Form\SchoolForm;
use Base\Controller\BaseController;
use Base\Entity\School;
use Zend\View\Model\ViewModel;

class SchoolController extends BaseController
{

    public function indexAction()
    {
        $form = new SchoolForm();
        $em = $this->getEntityManager();
        /** @var  $qb \Base\Entity\School */
        $qb = $em->createQueryBuilder()
            ->select('u')
            ->from('Base\Entity\School','u')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
        if ($qb) {
           // $form->bind($qb);
            $form->get('chinese_name')->setValue($qb->getChineseName());
            $form->get('english_name')->setValue($qb->getEnglishName());
            $form->get('note')->setValue($qb->getNote());
        }


        $viewModel = new ViewModel();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
//                // raw data
//                print_r($data);
//                // filter 過的 data
//                print_r($form->getData());

                $data = $form->getData();



                $qb = $em->createQueryBuilder()
                    ->select('u')
                    ->from('Base\Entity\School', 'u')
                    ->getQuery()
                    ->getResult();


                if (count($qb) == 0)
                    $schoolRes = new School();
                else
                    $schoolRes = $qb[0];

                $schoolRes->setChineseName($data['chinese_name']);
                $schoolRes->setEnglishName($data['english_name']);
                $schoolRes->setNote($data['note']);
                $em->persist($schoolRes);

                $em->flush();

                $this->flashmessenger()->addSuccessMessage('學校資料修改成功');
                return $this->redirect()->toUrl('/');


            }

        }


        $viewModel->setVariable('form', $form);

        return $viewModel;
    }

}