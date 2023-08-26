<?php

namespace app\controllers;

use MF\controller\Action;
use MF\model\Container;

class IndexController extends Action{

    public function index()
    {
        $this->render('index','layout');
    }

    public function appointment()
    {
        if(isset($_GET['action'])){
            if($_GET['action'] === 'show'){
                $this->showAppointments();
            }
            elseif($_GET['action'] === 'create'){
                $this->createAppointment();
            }
        }
    }

    private function createAppointment()
    {
        $datetime = str_replace('T',' ',$_POST['datetime']);
        $appointment = Container::getModel('Appointment');
        $appointment->create($_POST['email'],$datetime,$_POST['name']);
        header('location:/appointment?action=show');
    }

    private function showAppointments()
    {
        $appointment = Container::getModel('Appointment');
        $this->view->data = $appointment->getAll();
        $this->render('appointments','layout');
    }

    public function email()
    {
    
        $appointments = Container::getModel('Appointment');
        $appointment = $appointments->getById($_GET['id']);
        $mailer = Container::getModel('Mailer');

        if($_GET['action'] === 'confirm'){
            $this->confirmAppointment($appointments,$appointment,$mailer);
        }
        elseif($_GET['action'] === 'cancel'){
            $this->cancelAppointment($appointments,$appointment,$mailer);
        }
        
        header('location:/appointment?action=show');
    }

    private function confirmAppointment($appointments,$appointment,$mailer)
    {
        $datetime = explode(' ',$appointment['data_hora']);
        $datetime[0] = date("d/m/Y",strtotime($datetime[0]));
        $datetime[1] = substr($datetime[1],0,5);
        $mailer->sendConfirmEmail($appointment['email_paciente'],$datetime,$appointment['nome_paciente']);
        $appointments->confirm($_GET['id']);
    }

    private function cancelAppointment($appointments,$appointment,$mailer)
    {
        $mailer->sendCancelEmail($appointment['email_paciente'],$appointment['nome_paciente']);
        $appointments->remove($appointment['id']);
    }
}
