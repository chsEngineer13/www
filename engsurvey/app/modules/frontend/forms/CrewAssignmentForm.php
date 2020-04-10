<?php
namespace Engsurvey\Frontend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;
use Engsurvey\Models\Branches;
use Engsurvey\Models\Crews;
use Engsurvey\Models\ConstructionProjects;
use Engsurvey\Models\ConstructionSites;

class CrewAssignmentForm extends Form
{
    /**
     * Инициализация формы.
     */
    public function initialize($entity = null, $options = [])
    {
        // Идентификатор назначения бригады.
        $this->add(new Hidden("id"));

        // Филиал.
        $branch = new Select(
            'branchId',
            Branches::find(['order' => 'sequenceNumber']),
            [
                'using' => ['id', 'displayName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => ''
            ]
        );
        $branch->setLabel('Филиал&nbsp;*');
        $branch->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать филиал.',
            ])
        ]);
        $this->add($branch);
        
        // Формирование запроса для получения бригад.
        $branchId = '00000000-0000-0000-0000-000000000000';
        if (!is_null($entity)) {
            $branchId = $entity->getBranchId();
        }
        
        $branchParameters = 
            [
                "branchId = '$branchId'",
                "order" => "crewName",
            ];

        // Бригада. Заполняется в представлении.
        $crew = new Select(
            'crewId',
            Crews::find($branchParameters),
            [
                'using' => ['id', 'crewName'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => '',
            ]
        );
        $crew->setLabel('Бригада&nbsp;*');
        $crew->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать бригаду.',
            ])
        ]);
        $this->add($crew);

        // Стройка.
        $constructionProject = new Select(
            'constructionProjectId',
            ConstructionProjects::find(['order' => 'code']),
            [
                'using' => ['id', 'code'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => '',
            ]
        );
        $constructionProject->setLabel('Стройка&nbsp;(шифр)&nbsp;*');
        $constructionProject->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать стройку.',
            ])
        ]);
        $this->add($constructionProject);
        
        // Формирование запроса для получения участков работ.
        $constructionProjectId = '00000000-0000-0000-0000-000000000000';
        if (!is_null($entity)) {
            $constructionProjectId = $entity->getConstructionProjectId();
        }
        
        $constructionSiteParameters = 
            [
                "constructionProjectId = '$constructionProjectId'",
                "order" => "siteNumber",
            ];        

        // Участок работ. Заполняется в представлении.
        $constructionSite = new Select(
            'constructionSiteId',
            ConstructionSites::find($constructionSiteParameters),
            [
                'using' => ['id', 'siteNumber'],
                'useEmpty'   => true,
                'emptyText'  => '. . .',
                'emptyValue' => '',
            ]
        );
        $constructionSite->setLabel('Участок работ&nbsp;№&nbsp;*');
        $constructionSite->addValidators([
            new PresenceOf([
                'message' => 'Необходимо выбрать участок работ.',
            ])
        ]);
        $this->add($constructionSite);

        // Дата начала работ на объекте.
        $startDate = new Text(
            'startDate',
            ['title' => 'Дата начала работ на объекте']
        );
        $startDate->setLabel('Начало работ&nbsp;*');
        $startDate->setFilters(['trim']);
        $startDate->addValidators([
            new PresenceOf([
                'message' => 'Необходимо заполнить дату начала работ',
            ])
        ]);
        $this->add($startDate);

        // Дата завершения работ на объекте.
        $endDate = new Text(
            'endDate',
            ['title' => 'Дата завершения работ на объекте']
        );
        $endDate->setLabel('Завершение работ&nbsp;*');
        $endDate->setFilters(['trim']);
        $endDate->addValidators([
            new PresenceOf([
                'message' => 'Необходимо заполнить дату завершения работ',
            ])
        ]);
        $this->add($endDate);

        // Комментарий.
        $comment = new TextArea(
            'comment',
            [
                'rows' => '5',
                'title' => 'Комментарий',
            ]
        );
        $comment->setLabel('Комментарий');
        $comment->setFilters(['trim']);
        $this->add($comment);

    }
}
