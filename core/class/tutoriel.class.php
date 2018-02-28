<?php

require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class tutoriel extends eqLogic
{

    /*************** Attributs ***************/

    /************* Static methods ************/

    /**
     * Tâche exécutée toutes les 30 minutes.
     *
     * @param null $_eqLogic_id Identifiant des objets
     */
    public static function cron30($_eqLogic_id = null)
    {
        // Récupère la liste des équipements
        if ($_eqLogic_id == null) {
            $eqLogics = self::byType('tutoriel', true);
        } else {
            $eqLogics = array(self::byId($_eqLogic_id));
        }
        // Met à jour l'ensemble des équipements
        foreach ($eqLogics as $tutorielObj) {
            // On récupère la commande 'data' appartenant à l'équipement
            $dataCmd = $tutorielObj->getCmd('info', 'data');
            // On lui ajoute un évènement avec pour information 'Données de test'
            $dataCmd->event(date('H:i'));
            // On sauvegarde cet évènement
            $dataCmd->save();
        }
    }

    /**************** Methods ****************/

    public function postUpdate()
    {
        $getDataCmd = $this->getCmd(null, 'data');
        if (!is_object($getDataCmd)) {
            // Création de la commande
            $cmd = new tutorielCmd();
            // Nom affiché
            $cmd->setName('Données');
            // Identifiant de la commande
            $cmd->setLogicalId('data');
            // Identifiant de l'équipement
            $cmd->setEqLogic_id($this->getId());
            // Type de la commande
            $cmd->setType('info');
            // Sous-type de la commande
            $cmd->setSubType('string');
            // Visibilité de la commande
            $cmd->setIsVisible(1);
            // Sauvegarde de la commande
            $cmd->save();
        }
        $getDataCmd = $this->getCmd(null, 'refresh');
        if (!is_object($getDataCmd)) {
            // Création de la commande
            $cmd = new tutorielCmd();
            // Nom affiché
            $cmd->setName('Rafraichir');
            // Identifiant de la commande
            $cmd->setLogicalId('refresh');
            // Identifiant de l'équipement
            $cmd->setEqLogic_id($this->getId());
            // Type de la commande
            $cmd->setType('action');
            // Sous-type de la commande
            $cmd->setSubType('other');
            // Visibilité de la commande
            $cmd->setIsVisible(1);
            // Sauvegarde de la commande
            $cmd->save();
        }
    }
    /********** Getters and setters **********/

}

class tutorielCmd extends cmd
{

    /*************** Attributs ***************/

    /************* Static methods ************/

    /**************** Methods ****************/

    public function execute($_options = array()) {
        // Test pour ne répondre qu'à la commande rafraichir
        if ($this->getLogicalId() == 'refresh') {
            // On récupère l'équipement à partir de l'identifiant fournit par la commande
            $tutorielObj = tutoriel::byId($this->getEqlogic_id());
            // On récupère la commande 'data' appartenant à l'équipement
            $dataCmd = $tutorielObj->getCmd('info', 'data');
            // On lui ajoute un évènement avec pour information 'Données de test'
            $dataCmd->event(date('H:i'));
            // On sauvegarde cet évènement
            $dataCmd->save();
        }
    }

    /********** Getters and setters **********/

}
