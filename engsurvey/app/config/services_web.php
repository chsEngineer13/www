<?php

use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Acl;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Role;

/**
 * Регистрация маршрутизатора.
 */
$di->setShared(
    'router',
    function() {
        return require APP_PATH . '/config/routes.php';
    }
);


/**
 * Компонент URL используется для создания всех видов URL-адресов в приложении.
 */
$di->setShared(
    'url',
    function () {
        $config = $this->getConfig();

        $url = new UrlResolver();
        $url->setBaseUri($config->app->baseUri);

        return $url;
    }
);


/**
 * Регистрация сервиса сессий для совместного доступа.
 * Сессия запускается при первом запросе сервиса каким-либо компонентом.
 */
$di->setShared(
    'session',
    function () {
        $session = new SessionAdapter();
        $session->start();

        return $session;
    }
);


/**
 * Регистрация компонентов сообщений "FlashSession"
 * с CSS классами Twitter Bootstrap 3.
 */
$di->set(
    'flashSession',
    function () {
        $flashSession = new FlashSession(
            [
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]
        );

        return $flashSession;
    }
);


/**
 * Access Control List
 * Reads privateResource as an array from the config object.
 */
$di->set('acl', function () {
    $acl = new AclList();

    // Указание "запрещено" по умолчанию для тех объектов,
    // которые не были занесены в список контроля доступа.
    $acl->setDefaultAction(Acl::DENY);

    // Создание ролей.
    $usersRole = new Role('users');
    $editorsRole = new Role('editors');
    $administratorsRole = new Role('administrators');

    // Добавление ролей в список ACL.
    $acl->addRole($usersRole);
    $acl->addRole($editorsRole);
    $acl->addRole($administratorsRole);
    
    // Определение резурсов, добавление резурсов с операциями в список ACL,
    // задание уровня доступа для ролей на определенный ресурс:

    // Branches
    $resource = new Resource('Branches');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'Branches', 'search');
    $acl->deny('users', 'Branches', 'new');
    $acl->deny('users', 'Branches', 'edit');
    $acl->deny('users', 'Branches', 'create');
    $acl->deny('users', 'Branches', 'update');
    $acl->deny('users', 'Branches', 'delete');
    
    $acl->allow('editors', 'Branches', 'search');
    $acl->allow('editors', 'Branches', 'new');
    $acl->allow('editors', 'Branches', 'edit');
    $acl->allow('editors', 'Branches', 'create');
    $acl->allow('editors', 'Branches', 'update');
    $acl->allow('editors', 'Branches', 'delete');
    
    $acl->allow('administrators', 'Branches', 'search');
    $acl->allow('administrators', 'Branches', 'new');
    $acl->allow('administrators', 'Branches', 'edit');
    $acl->allow('administrators', 'Branches', 'create');
    $acl->allow('administrators', 'Branches', 'update');
    $acl->allow('administrators', 'Branches', 'delete');

    // ConstructionProjects
    $resource = new Resource('ConstructionProjects');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'ConstructionProjects', 'search');
    $acl->deny('users', 'ConstructionProjects', 'new');
    $acl->deny('users', 'ConstructionProjects', 'edit');
    $acl->deny('users', 'ConstructionProjects', 'create');
    $acl->deny('users', 'ConstructionProjects', 'update');
    $acl->deny('users', 'ConstructionProjects', 'delete');
    
    $acl->allow('editors', 'ConstructionProjects', 'search');
    $acl->allow('editors', 'ConstructionProjects', 'new');
    $acl->allow('editors', 'ConstructionProjects', 'edit');
    $acl->allow('editors', 'ConstructionProjects', 'create');
    $acl->allow('editors', 'ConstructionProjects', 'update');
    $acl->allow('editors', 'ConstructionProjects', 'delete');
    
    $acl->allow('administrators', 'ConstructionProjects', 'search');
    $acl->allow('administrators', 'ConstructionProjects', 'new');
    $acl->allow('administrators', 'ConstructionProjects', 'edit');
    $acl->allow('administrators', 'ConstructionProjects', 'create');
    $acl->allow('administrators', 'ConstructionProjects', 'update');
    $acl->allow('administrators', 'ConstructionProjects', 'delete');
    
    
    // ConstructionSites
    $resource = new Resource('ConstructionSites');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'ConstructionSites', 'search');
    $acl->deny('users', 'ConstructionSites', 'new');
    $acl->deny('users', 'ConstructionSites', 'edit');
    $acl->deny('users', 'ConstructionSites', 'create');
    $acl->deny('users', 'ConstructionSites', 'update');
    $acl->deny('users', 'ConstructionSites', 'delete');
    
    $acl->allow('editors', 'ConstructionSites', 'search');
    $acl->allow('editors', 'ConstructionSites', 'new');
    $acl->allow('editors', 'ConstructionSites', 'edit');
    $acl->allow('editors', 'ConstructionSites', 'create');
    $acl->allow('editors', 'ConstructionSites', 'update');
    $acl->allow('editors', 'ConstructionSites', 'delete');
    
    $acl->allow('administrators', 'ConstructionSites', 'search');
    $acl->allow('administrators', 'ConstructionSites', 'new');
    $acl->allow('administrators', 'ConstructionSites', 'edit');
    $acl->allow('administrators', 'ConstructionSites', 'create');
    $acl->allow('administrators', 'ConstructionSites', 'update');
    $acl->allow('administrators', 'ConstructionSites', 'delete');
    
    
    // CrewAssignments
    $resource = new Resource('CrewAssignments');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'CrewAssignments', 'search');
    $acl->deny('users', 'CrewAssignments', 'new');
    $acl->deny('users', 'CrewAssignments', 'edit');
    $acl->deny('users', 'CrewAssignments', 'create');
    $acl->deny('users', 'CrewAssignments', 'update');
    $acl->deny('users', 'CrewAssignments', 'delete');
    
    $acl->allow('editors', 'CrewAssignments', 'search');
    $acl->allow('editors', 'CrewAssignments', 'new');
    $acl->allow('editors', 'CrewAssignments', 'edit');
    $acl->allow('editors', 'CrewAssignments', 'create');
    $acl->allow('editors', 'CrewAssignments', 'update');
    $acl->allow('editors', 'CrewAssignments', 'delete');
    
    $acl->allow('administrators', 'CrewAssignments', 'search');
    $acl->allow('administrators', 'CrewAssignments', 'new');
    $acl->allow('administrators', 'CrewAssignments', 'edit');
    $acl->allow('administrators', 'CrewAssignments', 'create');
    $acl->allow('administrators', 'CrewAssignments', 'update');
    $acl->allow('administrators', 'CrewAssignments', 'delete');
    
    
    // Crews
    $resource = new Resource('Crews');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'Crews', 'search');
    $acl->deny('users', 'Crews', 'new');
    $acl->deny('users', 'Crews', 'edit');
    $acl->deny('users', 'Crews', 'create');
    $acl->deny('users', 'Crews', 'update');
    $acl->deny('users', 'Crews', 'delete');
    
    $acl->allow('editors', 'Crews', 'search');
    $acl->allow('editors', 'Crews', 'new');
    $acl->allow('editors', 'Crews', 'edit');
    $acl->allow('editors', 'Crews', 'create');
    $acl->allow('editors', 'Crews', 'update');
    $acl->allow('editors', 'Crews', 'delete');
    
    $acl->allow('administrators', 'Crews', 'search');
    $acl->allow('administrators', 'Crews', 'new');
    $acl->allow('administrators', 'Crews', 'edit');
    $acl->allow('administrators', 'Crews', 'create');
    $acl->allow('administrators', 'Crews', 'update');
    $acl->allow('administrators', 'Crews', 'delete');
    
    
    // EmployeeGroupMemberships
    $resource = new Resource('EmployeeGroupMemberships');
    $acl->addResource($resource, ['search', 'add', 'delete']);
    
    $acl->allow('users', 'EmployeeGroupMemberships', 'search');
    $acl->deny('users', 'EmployeeGroupMemberships', 'add');
    $acl->deny('users', 'EmployeeGroupMemberships', 'delete');
    
    $acl->allow('editors', 'EmployeeGroupMemberships', 'search');
    $acl->allow('editors', 'EmployeeGroupMemberships', 'add');
    $acl->allow('editors', 'EmployeeGroupMemberships', 'delete');
    
    $acl->allow('administrators', 'EmployeeGroupMemberships', 'search');
    $acl->allow('administrators', 'EmployeeGroupMemberships', 'add');
    $acl->allow('administrators', 'EmployeeGroupMemberships', 'delete');

    
    // EmployeeGroups
    $resource = new Resource('EmployeeGroups');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'EmployeeGroups', 'search');
    $acl->deny('users', 'EmployeeGroups', 'new');
    $acl->deny('users', 'EmployeeGroups', 'edit');
    $acl->deny('users', 'EmployeeGroups', 'create');
    $acl->deny('users', 'EmployeeGroups', 'update');
    $acl->deny('users', 'EmployeeGroups', 'delete');
    
    $acl->allow('editors', 'EmployeeGroups', 'search');
    $acl->allow('editors', 'EmployeeGroups', 'new');
    $acl->allow('editors', 'EmployeeGroups', 'edit');
    $acl->allow('editors', 'EmployeeGroups', 'create');
    $acl->allow('editors', 'EmployeeGroups', 'update');
    $acl->allow('editors', 'EmployeeGroups', 'delete');
    
    $acl->allow('administrators', 'EmployeeGroups', 'search');
    $acl->allow('administrators', 'EmployeeGroups', 'new');
    $acl->allow('administrators', 'EmployeeGroups', 'edit');
    $acl->allow('administrators', 'EmployeeGroups', 'create');
    $acl->allow('administrators', 'EmployeeGroups', 'update');
    $acl->allow('administrators', 'EmployeeGroups', 'delete');
    
    
    // Employees
    $resource = new Resource('Employees');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'Employees', 'search');
    $acl->deny('users', 'Employees', 'new');
    $acl->deny('users', 'Employees', 'edit');
    $acl->deny('users', 'Employees', 'create');
    $acl->deny('users', 'Employees', 'update');
    $acl->deny('users', 'Employees', 'delete');
    
    $acl->allow('editors', 'Employees', 'search');
    $acl->allow('editors', 'Employees', 'new');
    $acl->allow('editors', 'Employees', 'edit');
    $acl->allow('editors', 'Employees', 'create');
    $acl->allow('editors', 'Employees', 'update');
    $acl->allow('editors', 'Employees', 'delete');
    
    $acl->allow('administrators', 'Employees', 'search');
    $acl->allow('administrators', 'Employees', 'new');
    $acl->allow('administrators', 'Employees', 'edit');
    $acl->allow('administrators', 'Employees', 'create');
    $acl->allow('administrators', 'Employees', 'update');
    $acl->allow('administrators', 'Employees', 'delete');
    
    
    // Organizations
    $resource = new Resource('Organizations');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'Organizations', 'search');
    $acl->deny('users', 'Organizations', 'new');
    $acl->deny('users', 'Organizations', 'edit');
    $acl->deny('users', 'Organizations', 'create');
    $acl->deny('users', 'Organizations', 'update');
    $acl->deny('users', 'Organizations', 'delete');
    
    $acl->allow('editors', 'Organizations', 'search');
    $acl->allow('editors', 'Organizations', 'new');
    $acl->allow('editors', 'Organizations', 'edit');
    $acl->allow('editors', 'Organizations', 'create');
    $acl->allow('editors', 'Organizations', 'update');
    $acl->allow('editors', 'Organizations', 'delete');
    
    $acl->allow('administrators', 'Organizations', 'search');
    $acl->allow('administrators', 'Organizations', 'new');
    $acl->allow('administrators', 'Organizations', 'edit');
    $acl->allow('administrators', 'Organizations', 'create');
    $acl->allow('administrators', 'Organizations', 'update');
    $acl->allow('administrators', 'Organizations', 'delete');
    
    
    // PerformanceDynamics
    $resource = new Resource('PerformanceDynamics');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'PerformanceDynamics', 'search');
    $acl->deny('users', 'PerformanceDynamics', 'new');
    $acl->deny('users', 'PerformanceDynamics', 'edit');
    $acl->deny('users', 'PerformanceDynamics', 'create');
    $acl->deny('users', 'PerformanceDynamics', 'update');
    $acl->deny('users', 'PerformanceDynamics', 'delete');
    
    $acl->allow('editors', 'PerformanceDynamics', 'search');
    $acl->allow('editors', 'PerformanceDynamics', 'new');
    $acl->allow('editors', 'PerformanceDynamics', 'edit');
    $acl->allow('editors', 'PerformanceDynamics', 'create');
    $acl->allow('editors', 'PerformanceDynamics', 'update');
    $acl->allow('editors', 'PerformanceDynamics', 'delete');
    
    $acl->allow('administrators', 'PerformanceDynamics', 'search');
    $acl->allow('administrators', 'PerformanceDynamics', 'new');
    $acl->allow('administrators', 'PerformanceDynamics', 'edit');
    $acl->allow('administrators', 'PerformanceDynamics', 'create');
    $acl->allow('administrators', 'PerformanceDynamics', 'update');
    $acl->allow('administrators', 'PerformanceDynamics', 'delete');
    
    
    // SurveyFacilities
    $resource = new Resource('SurveyFacilities');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete', 'importObjectsXlsx']);
    
    $acl->allow('users', 'SurveyFacilities', 'search');
    $acl->deny('users', 'SurveyFacilities', 'new');
    $acl->deny('users', 'SurveyFacilities', 'edit');
    $acl->deny('users', 'SurveyFacilities', 'create');
    $acl->deny('users', 'SurveyFacilities', 'update');
    $acl->deny('users', 'SurveyFacilities', 'delete');
    $acl->deny('users', 'SurveyFacilities', 'importObjectsXlsx');
    
    $acl->allow('editors', 'SurveyFacilities', 'search');
    $acl->allow('editors', 'SurveyFacilities', 'new');
    $acl->allow('editors', 'SurveyFacilities', 'edit');
    $acl->allow('editors', 'SurveyFacilities', 'create');
    $acl->allow('editors', 'SurveyFacilities', 'update');
    $acl->allow('editors', 'SurveyFacilities', 'delete');
    $acl->allow('editors', 'SurveyFacilities', 'importObjectsXlsx');
    
    $acl->allow('administrators', 'SurveyFacilities', 'search');
    $acl->allow('administrators', 'SurveyFacilities', 'new');
    $acl->allow('administrators', 'SurveyFacilities', 'edit');
    $acl->allow('administrators', 'SurveyFacilities', 'create');
    $acl->allow('administrators', 'SurveyFacilities', 'update');
    $acl->allow('administrators', 'SurveyFacilities', 'delete');
    $acl->allow('administrators', 'SurveyFacilities', 'importObjectsXlsx');
    
    
    // WorkTypes
    $resource = new Resource('WorkTypes');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'WorkTypes', 'search');
    $acl->deny('users', 'WorkTypes', 'new');
    $acl->deny('users', 'WorkTypes', 'edit');
    $acl->deny('users', 'WorkTypes', 'create');
    $acl->deny('users', 'WorkTypes', 'update');
    $acl->deny('users', 'WorkTypes', 'delete');
    
    $acl->allow('editors', 'WorkTypes', 'search');
    $acl->allow('editors', 'WorkTypes', 'new');
    $acl->allow('editors', 'WorkTypes', 'edit');
    $acl->allow('editors', 'WorkTypes', 'create');
    $acl->allow('editors', 'WorkTypes', 'update');
    $acl->allow('editors', 'WorkTypes', 'delete');
    
    $acl->allow('administrators', 'WorkTypes', 'search');
    $acl->allow('administrators', 'WorkTypes', 'new');
    $acl->allow('administrators', 'WorkTypes', 'edit');
    $acl->allow('administrators', 'WorkTypes', 'create');
    $acl->allow('administrators', 'WorkTypes', 'update');
    $acl->allow('administrators', 'WorkTypes', 'delete');
    
    
    // Contracts
    $resource = new Resource('Contracts');
    $acl->addResource($resource, ['search', 'properties', 'new', 'edit', 'create', 'update', 'delete']);
    
    $acl->allow('users', 'Contracts', 'search');
    $acl->allow('users', 'Contracts', 'properties');
    $acl->deny('users', 'Contracts', 'new');
    $acl->deny('users', 'Contracts', 'edit');
    $acl->deny('users', 'Contracts', 'create');
    $acl->deny('users', 'Contracts', 'update');
    $acl->deny('users', 'Contracts', 'delete');
    
    $acl->allow('editors', 'Contracts', 'search');
    $acl->allow('editors', 'Contracts', 'properties');
    $acl->allow('editors', 'Contracts', 'new');
    $acl->allow('editors', 'Contracts', 'edit');
    $acl->allow('editors', 'Contracts', 'create');
    $acl->allow('editors', 'Contracts', 'update');
    $acl->allow('editors', 'Contracts', 'delete');
    
    $acl->allow('administrators', 'Contracts', 'search');
    $acl->allow('administrators', 'Contracts', 'properties');
    $acl->allow('administrators', 'Contracts', 'new');
    $acl->allow('administrators', 'Contracts', 'edit');
    $acl->allow('administrators', 'Contracts', 'create');
    $acl->allow('administrators', 'Contracts', 'update');
    $acl->allow('administrators', 'Contracts', 'delete');
    
    
    // ContractFiles
    $resource = new Resource('ContractFiles');
    $acl->addResource($resource, ['search', 'upload', 'delete']);
    
    $acl->allow('users', 'ContractFiles', 'search');
    $acl->deny('users', 'ContractFiles', 'upload');
    $acl->deny('users', 'ContractFiles', 'delete');
    
    $acl->allow('editors', 'ContractFiles', 'search');
    $acl->allow('editors', 'ContractFiles', 'upload');
    $acl->allow('editors', 'ContractFiles', 'delete');
    
    $acl->allow('administrators', 'ContractFiles', 'search');
    $acl->allow('administrators', 'ContractFiles', 'upload');
    $acl->allow('administrators', 'ContractFiles', 'delete');
    
    
    // ContractStages
    $resource = new Resource('ContractStages');
    $acl->addResource($resource, ['search', 'new', 'edit', 'create', 'update', 'delete',
        'exportXlsx', 'importXlsx']);
    
    $acl->allow('users', 'ContractStages', 'search');
    $acl->deny('users', 'ContractStages', 'new');
    $acl->deny('users', 'ContractStages', 'edit');
    $acl->deny('users', 'ContractStages', 'create');
    $acl->deny('users', 'ContractStages', 'update');
    $acl->deny('users', 'ContractStages', 'delete');
    $acl->deny('users', 'ContractStages', 'exportXlsx');
    $acl->deny('users', 'ContractStages', 'importXlsx');

    $acl->allow('editors', 'ContractStages', 'search');
    $acl->allow('editors', 'ContractStages', 'new');
    $acl->allow('editors', 'ContractStages', 'edit');
    $acl->allow('editors', 'ContractStages', 'create');
    $acl->allow('editors', 'ContractStages', 'update');
    $acl->allow('editors', 'ContractStages', 'delete');
    $acl->allow('editors', 'ContractStages', 'exportXlsx');
    $acl->allow('editors', 'ContractStages', 'importXlsx');
    
    $acl->allow('administrators', 'ContractStages', 'search');
    $acl->allow('administrators', 'ContractStages', 'new');
    $acl->allow('administrators', 'ContractStages', 'edit');
    $acl->allow('administrators', 'ContractStages', 'create');
    $acl->allow('administrators', 'ContractStages', 'update');
    $acl->allow('administrators', 'ContractStages', 'delete');
    $acl->allow('administrators', 'ContractStages', 'exportXlsx');
    $acl->allow('administrators', 'ContractStages', 'importXlsx');

    return $acl;
});
