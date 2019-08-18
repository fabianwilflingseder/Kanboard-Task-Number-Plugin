<?php

namespace Kanboard\Plugin\Tasknumberplugin\Model;

use Kanboard\Core\Base;
use SimpleValidator\Validator;
use SimpleValidator\Validators;

/**
 * Task Number Plugin
 *
 * @package  model
 * @author   Fabian Wilflingseder
 */
class TaskNumberModel extends Base
{
    /**
     * Get the ticket number schema for a project
     *
     * @access public
     * @param  integer  $project_id
     * @return string
     */
    public function getTicketNumberSchemaByProject($project_id)
    {
        return $this->projectMetadataModel->get($project_id, SETTING_PROJECT_SCHEMA, '');
    }

    /**
     * Get the feature activation status for project
     *
     * @access public
     * @param  integer  $project_id
     * @return boolean
     */
    public function isTicketNumberFeatureEnabledByProject($project_id)
    {
        return $this->projectMetadataModel->get($project_id, SETTING_TICKET_NUMBERS_ENABLED, 0);
    }

    /**
     * Get the current number for a project
     *
     * @access public
     * @param  integer  $project_id
     * @return integer
     */
    public function getCurrentTicketNumberForProject($project_id)
    {
        return $this->projectMetadataModel->get($project_id, SETTING_CURRENT_NUMBER, 1);
    }

    /**
     * Get the current number for a project and increases the next number
     *
     * @access public
     * @param  integer  $project_id
     * @return integer
     */
    public function resolveAndUpdateTicketNumber($project_id)
    {
        $currentNumber = $this->projectMetadataModel->get($project_id, SETTING_CURRENT_NUMBER);
        $this->updateCurrentTicketNumberForProject($project_id, $currentNumber + 1);
        return $currentNumber;
    }

    /**
     * Update the setting of the activation of the plugin feature
     *
     * @access public
     * @param integer   $project_id  Project ID
     * @param boolean   $isEnabled   Feature enabled status
     * @return boolean|integer
     */
    public function updateTicketNumberActivationStatus($project_id, $isEnabled)
    {
        $this->projectMetadataModel->save($project_id, [SETTING_TICKET_NUMBERS_ENABLED => $isEnabled]);
        return $isEnabled;
    }

    /**
     * Update the schema for the ticket numbers
     *
     * @access public
     * @param integer   $project_id           Project ID
     * @param boolean   $ticketNumberSchema   Schema for ticket numbers
     * @return string
     */
    public function updateTicketNumberSchema($project_id, $ticketNumberSchema)
    {
        $this->projectMetadataModel->save($project_id, [SETTING_PROJECT_SCHEMA => $ticketNumberSchema]);
        return $ticketNumberSchema;
    }

    /**
     * Update the current number for a project
     *
     * @access public
     * @param integer   $project_id   Project ID
     * @param integer   $number       Number
     * @return number
     */
    public function updateCurrentTicketNumberForProject($project_id, $number)
    {
        $this->projectMetadataModel->save($project_id, [SETTING_CURRENT_NUMBER => $number]);
        return $number;
    }

    /**
     * Update the current number for a task
     *
     * @access public
     * @param integer   $taskId       Task ID
     * @param integer   $number       Number
     * @return number
     */
    public function updateCurrentTicketNumberForTask($taskId, $number)
    {
        $this->taskMetadataModel->save($taskId, [TASK_TICKET_NUMBER => $number]);
        return $number;
    }
}