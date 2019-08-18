<?php
namespace Kanboard\Plugin\Tasknumberplugin\Controller;
use Kanboard\Controller\BaseController;
/**
 * Task Number Controller
 *
 * @package  controller
 * @author   Fabian Wilflingseder
 */
class TaskNumberController extends BaseController
{
    public function show(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $val = array(
            'schema' => $this->taskNumberModel->getTicketNumberSchemaByProject($project['id']),
            'ticketsenabled' => $this->taskNumberModel->isTicketNumberFeatureEnabledByProject($project['id']),
            'number' => $this->taskNumberModel->getCurrentTicketNumberForProject($project['id']),
            'project_id' => $project['id']
        );
        $this->response->html($this->helper->layout->project('tasknumberplugin:tasknumber/numbersetting', array(
            'project' => $project,
            'values' => $values + $val,
            'errors' => $errors,
        )));
    }

    public function save()
    {
        $values =  $this->request->getValues();
        $project_id = $values['project_id'];

        if(isset($values['ticketsenabled']) && ($values['ticketsenabled'] == true  || $values['ticketsenabled'] == 1)) {
            $this->taskNumberModel->updateTicketNumberActivationStatus($project_id, 1);
        }  else {
            $this->taskNumberModel->updateTicketNumberActivationStatus($project_id, 0);
            $this->flash->failure(t('Unable to save the ticket number'));
        }
        if(isset($values['schema'])) {
            $this->taskNumberModel->updateTicketNumberSchema($project_id, $values['schema']);
        } else $this->flash->failure(t('Unable to save the ticket schema'));
        if(isset($values['number'])) {
            $this->taskNumberModel->updateCurrentTicketNumberForProject($project_id, $values['number']);
        } else $this->flash->failure(t('Unable to save the ticket number'));
        
        $this->response->redirect($this->helper->url->to('TaskNumberController', 'show', array('plugin' => 'Tasknumberplugin', 'project_id' => $project_id)));
    }
}