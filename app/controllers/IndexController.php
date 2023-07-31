<?php

namespace app\controllers;

use MF\controller\Action;
use MF\model\Container;

class IndexController extends Action{

    public function index(){

        if(isset($_GET['create'])){
            $datetime = str_replace('T',' ',$_POST['datetime']);
            $appointment = Container::getModel('Appointment');
            $appointment->createAppointment($_POST['email'],$datetime,$_POST['name']);
            header('location:?show=true');
        }
        if(isset($_GET['show'])){
            $appointment = Container::getModel('Appointment');
            $this->view->data = $appointment->getAppointments();
        }
    
        $this->render('index','layout');
    }

    public function email()
    {
        if(isset($_GET['action'])){
            $appointments = Container::getModel('Appointment');
            $appointment = $appointments->getAppointmentId($_GET['id']);
            $mailer = Container::getModel('Mailer');

            if($_GET['action'] === 'confirm'){
                $datetime = explode(' ',$appointment['data_hora']);
                $datetime[0] = date("d/m/Y",strtotime($datetime[0]));
                $datetime[1] = substr($datetime[1],0,5);
                $mailer->sendConfirmEmail($appointment['email_paciente'],$datetime,$appointment['nome_paciente']);
                $appointments->confirmAppointment($_GET['id']);
            }
            if($_GET['action'] === 'cancel'){
                $mailer->sendCancelEmail($appointment['email_paciente'],$appointment['nome_paciente']);
                $appointments->removeAppointment($appointment['id']);
            }
        }
        
        header('location:/?show=true');
    }
}
