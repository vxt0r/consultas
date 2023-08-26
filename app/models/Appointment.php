<?php

namespace app\models;

use DateTime;
use MF\model\Model;


class Appointment extends Model{

    /**
     * @param string
     * @param string
     * @param string
     * @return void
     */
    public function create($email,$datetime,$name)
    {
        $query = '  INSERT INTO consulta(email_paciente,data_hora,nome_paciente)
                    VALUES (?,?,?)';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1,$email);
        $stmt->bindValue(2,$datetime);
        $stmt->bindValue(3,$name);

        if($this->invalidAppointmentHour($datetime) || $this->invalidAppointmentDay($datetime)){
            header('location:/?error=create');
            exit();
        }

        try {
            $stmt->execute();
        } catch (\PDOException $e) {
            header('location:/?error=create');
            exit();
        }
    }

    /**
     * @return array
     */
    public function getAll(){
        $query = '  SELECT id,nome_paciente,data_hora, email_paciente, confirmada 
                    FROM consulta
                    ORDER BY data_hora';
        return $this->db->query($query)->fetchAll();
    }

    /**
     * @param string|int
     * @return array
     */
    public function getById($id){
        $query = '  SELECT id,nome_paciente,data_hora, email_paciente, confirmada 
                    FROM consulta
                    WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1,$id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * @param string|int
     * @return void 
     */
    public function remove($id)
    {
        $query = 'DELETE FROM consulta WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1,$id); 
        $stmt->execute();
    }

    /**
     * @param string|int
     * @return void
     */
    public function confirm($id)    
    {
        $query = 'UPDATE consulta SET confirmada = 1 WHERE id = ?';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1,$id); 
        $stmt->execute();
    }

    /**
     * @param string
     * @return bool
     */
    private function invalidAppointmentHour($datetime){
        $entry = strtotime('08:00'); 
        $exit = strtotime('18:00');
        $hour = strtotime(explode(' ',$datetime)[1]);

        if($hour < $entry || $hour >= $exit){
            return true;
        }

        return false;
    }

     /**
     * @param string
     * @return bool
     */
    private function invalidAppointmentDay($datetime){
        $date = explode(' ',$datetime)[0];
        $day = (new DateTime($date))->format('D');

        if($day === 'Sun' || $day === 'Sat'){
            return true;
        }

        return false;
    }

}
