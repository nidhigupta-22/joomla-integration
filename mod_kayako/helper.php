<?php
/**
 * ###############################################
 *
 * Kayako Module
 * _______________________________________________
 *
 * @author        Rahul Bhattacharya
 *
 * @package       mod_kayako
 * @copyright    Copyright (c) 2001-2012, Kayako
 * @license      http://www.kayako.com/license
 * @link        http://www.kayako.com
 *
 * ###############################################
 */

defined('_JEXEC') or die;

class JTickets
{
    /**
     * List of departments
     * @var array
     */
    public $all_departments = array();
    /**
     * List of priorities (eg. Normal, Medium, High)
     * @var kyResultSet
     */
    public $priority_list = null;
    /**
     * List of status (eg. Open, Closed)
     * @var kyResultSet
     */
    public $status_list = null;
    /**
     * List of ticket type (eg. Issue, Bug, Task)
     * @var kyResultSet
     */
    public $ticket_type = null;
    /**
     * Initialize client configuration object.
     */
    public function init()
    {
        $configObj = JModuleHelper::getModule('mod_kayako')->params;
        $configValue = json_decode($configObj, 1);
        $config = $config = new kyConfig($configValue['apiurl'], $configValue['apikey'], $configValue['secretkey']);
        $config->setIsStandardURLType(true);
        kyConfig::set($config);
    }

    /**
     * Fetch the list of departments
     */
    public function fetchAllDepartment()
    {
        $this->init();
        $departments_tree = $this->getDepartmentsTree();
        foreach ($departments_tree as $key => $val) {
            $options_array[$key] = $val['department'];
            foreach ($val['child_departments'] as $child_dept_key => $child_dept_value) {
                $options_array[$child_dept_key] = $child_dept_value;
            }
        }
        $this->all_departments =  $options_array;
        $this->priority_list = kyTicketPriority::getAll();
        $this->status_list   = kyTicketStatus::getAll();
        $this->ticket_type   = kyTicketType::getAll();
    }

    /**
     * Fetch tickets on the basis of department id
     */
    public function view($get_dep_id)
    {
            $department = kyDepartment::get($get_dep_id);
            $user = kyUser::search($this->email);
            $this->tickets = kyTicket::getAll($department, array(), array(), $user)->getRawArray();
    }

    /**
     * Sort departments by parent id
     *
     * return array
     */
    protected function getDepartmentsTree()
    {
        $departments_tree = array();
        $all_departments = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);
        $top_departments = $all_departments->filterByParentDepartmentId(null)->orderByDisplayOrder();
        foreach ($top_departments as $top_department) {
            $departments_tree[$top_department->getId()] = array(
                'department' => $top_department->getTitle()
            );
            $departments_tree[$top_department->getId()]['child_departments'] = array();
            foreach ($all_departments->filterByParentDepartmentId($top_department->getId())->orderByDisplayOrder() as $child_department) {
                $departments_tree[$top_department->getId()]['child_departments'][$child_department->getId()] = $child_department->getTitle();
            }
        }
        return $departments_tree;
    }

    /**
     * Create urgent ticket providing only name and e-mail of the user.
     *
     * @return ticket mask id
     */
    public function createTickets()
    {
        $input = JFactory::getApplication()->input;
        $form_value = $input->getArray(array('fname' => null, 'mail' => null, 'message' => null, 'subject' => null, 'dep_type' => null, 'priority' => null, 'status' => null, 'type' => null));

        if ($form_value['dep_type'] == null || $form_value['fname'] == null || $form_value['mail'] == null || $form_value['priority'] == null || $form_value['status'] == null || $form_value['type'] == null || $form_value['subject'] == null || $form_value['message'] == null) {
            return false;
        } else {
            require_once ('KayakoAPILibrary/kyIncludes.php');

            JTickets::init();
            kyTicket::setDefaults($form_value['status'], $form_value['priority'], $form_value['type']);

            //Create urgent ticket providing only name and e-mail of the user.
            $ticket = kyTicket::createNewAuto(kyDepartment::get($form_value['dep_type']), $form_value['fname'], $form_value['mail'], $form_value['message'], $form_value['subject'])->create();

            //get ticket id
            $ticket_mask_id = $ticket->getDisplayId();

            return $ticket_mask_id;

        }
    }

    /**
     * Edit the ticket of the user
     *
     * @return bool
     */
    public function editTicket()
    {
        $input = JFactory::getApplication()->input;
        $form_value = $input->getArray(array('priority' => null, 'status' => null, 'id' => null));

        if ($form_value['id'] == null || $form_value['status'] == null || $form_value['priority'] == null) {
            return false;
        } else {
            require_once ('modules/mod_kayako/KayakoAPILibrary/kyIncludes.php');

            JTickets::init();
            $ticket_object = kyTicket::get($form_value['id']);
            $ticket_object->setStatusId($form_value['status']);
            $ticket_object->setPriorityId($form_value['priority']);
            $ticket_object->update();

            return true;
        }
    }

    /**
     * Post a message to the user
     *
     * @return bool
     */
    public function postReply()
    {
        require_once ('modules/mod_kayako/KayakoAPILibrary/kyIncludes.php');

        $confObject = JFactory::getApplication();
        $tmp_path = $confObject->getCfg('tmp_path');

        JTickets::init();

        //Fetch Post data
        $input = JFactory::getApplication()->input;

        $form_value = $input->getArray(array('content' => null, 'id' => null, 'replyattachments' => null));
        $img = $_FILES['replyattachments']['name'];

        //Fetch the email of login User
        $email = JFactory::getUser()->email;
        $user  = kyUser::search($email)->getRawArray();

        $ticket_object = kyTicket::get($form_value['id']);

        $user_reply_post  = $ticket_object->newPost($user[0], $form_value['content'])->create();

        foreach ($img as $key => $value) {
            $name = time() . $_FILES["replyattachments"]["name"][$key];
            $temp_name = $_FILES["replyattachments"]["tmp_name"][$key];
            $size = $_FILES["replyattachments"]["size"][$key];
            if($size < 1024*1024) {
                move_uploaded_file($temp_name, $tmp_path ."/". $name);
                $user_reply_post->newAttachmentFromFile(JURI::Root() . "tmp/" . $name)
                              ->create();

            }
        }

        return true;
    }

    protected function getExtention($image_name)
    {
        return substr($image_name, strrpos($image_name, '.') + 1);
    }
}