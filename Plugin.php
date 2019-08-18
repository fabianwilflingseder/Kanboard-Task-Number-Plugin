<?php

namespace Kanboard\Plugin\Tasknumberplugin;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;

class Plugin extends Base
{
    public function initialize()
    {
        define('SETTING_PROJECT_SCHEMA', 'TasknumberpluginTicketNumberSchema');
        define('SETTING_TICKET_NUMBERS_ENABLED', 'TasknumberpluginTicketNumberEnabled');
        define('SETTING_CURRENT_NUMBER', 'TasknumberpluginTicketNumberCurrentNumber');
        define('TASK_TICKET_NUMBER', 'TasknumberpluginTicketNumberTaskTicketNumber');
        $this->hook->on('model:task:creation:aftersave', function ($taskId) {
            $arr = $this->taskFinderModel->getById($taskId);
            $projectId = $arr['project_id'];
            if($this->taskNumberModel->isTicketNumberFeatureEnabledByProject($projectId)) {
                $ticketSchema = $this->taskNumberModel->getTicketNumberSchemaByProject($projectId);
                $nr = $this->taskNumberModel->resolveAndUpdateTicketNumber($projectId);
                $ticketNumber = $ticketSchema . '-' . $nr;
                $values = array(
                    'id' => $taskId,
                    'title' => $ticketNumber . ': ' . $arr['title']
                );
                if(empty($arr['reference'])) {
                    $values['reference'] = $ticketNumber;
                }
                $this->taskModificationModel->update($values, false);
                $this->taskNumberModel->updateCurrentTicketNumberForTask($taskId, $ticketNumber . ': ' . $arr['title']);
            }
        });
        $this->template->hook->attach('template:project:sidebar', 'tasknumberplugin:project/sidebar');
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Task Number Plugin';
    }

    public function getPluginDescription()
    {
        return t('Adds a project unique task number to each new task');
    }

    public function getClasses()
    {
        return array(
            'Plugin\Tasknumberplugin\Model' => array(
                'TaskNumberModel'
            )
        );
    }

    public function getPluginAuthor()
    {
        return 'Fabian Wilflingseder';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://www.wilflingseder.work/kanboard-ticket-number-plugin/';
    }

    public function getCompatibleVersion()
    {
        // Examples:
        // >=1.0.37
        // <1.0.37
        // <=1.0.37
        return '>=1.0.37'; // version at development time
    }
}

