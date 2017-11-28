<?php

class login_model extends Model {
    /* Método construtor */

    function Login_model($servidor_id = null) {
        parent::Model();
    }

    function autenticar($usuario, $senha) {
//        $this->db->select(' o.operador_id,
//                                o.perfil_id,
//                                p.nome as perfil,
//                                a.modulo_id'
//        );
//        $this->db->from('tb_operador o');
//        $this->db->join('tb_perfil p', 'p.perfil_id = o.perfil_id');
//        $this->db->join('tb_acesso a', 'a.perfil_id = o.perfil_id', 'left');
//        $this->db->where('o.usuario', $usuario);
//        $this->db->where('o.senha', md5($senha));
//        $this->db->where('o.ativo = true');
//        $this->db->where('p.ativo = true');
//        $return = $this->db->get()->result();

        $this->db->select('ae.paciente_id');
        $this->db->from('tb_agenda_exames ae');
        $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id');
        $this->db->where('p.cpf', $usuario);
        $this->db->where('ae.senha', md5($senha));
        $return = $this->db->get()->result();

//        $this->db->select('empresa_id,
//                            nome');
//        $this->db->from('tb_empresa');
//        $this->db->where('empresa_id', $empresa);
//        $retorno = $this->db->get()->result();



        if (isset($return) && count($return) > 0) {

            $p = array(
                'autenticado' => true,
                'login_paciente' => true,
                'operador_id' => $return[0]->paciente_id,
                'paciente_id' => $return[0]->paciente_id
            );
            $this->session->set_userdata($p);
            return true;
        } else {
            $this->session->sess_destroy();
            return false;
        }
    }

    function autenticarcpfemail($usuario, $email) {
        $horario = date("Y-m-d H:i:s");

        $this->db->select('p.paciente_id');
        $this->db->from('tb_paciente p');
//        $this->db->join('tb_agenda_exames ae', 'p.paciente_id = ae.paciente_id', 'left');
        $this->db->where('p.cpf', $usuario);
        $this->db->where('p.cns', $email);
        $return = $this->db->get()->result();
//        var_dump($return);
//        die;
        if (count($return) > 0) {
            $paciente_id = $return[0]->paciente_id;

            $this->db->set('ativo', 'f');
            $this->db->set('cancelada', 'f');
            $this->db->set('confirmado', 'f');
            $this->db->set('situacao', 'OK');
            $this->db->set('paciente_id', $paciente_id);
            $this->db->set('data_atualizacao', $horario);
            $this->db->insert('tb_agenda_exames');
            $agenda_exames_id = $this->db->insert_id();
            $data['agenda_exames_id'] = $agenda_exames_id;
            $this->db->set('senha', md5($agenda_exames_id));
            $this->db->where('agenda_exames_id', $agenda_exames_id);
            $this->db->update('tb_agenda_exames');


            $this->db->select('ae.paciente_id, ae.agenda_exames_id, p.nome as paciente');
            $this->db->from('tb_agenda_exames ae');
            $this->db->join('tb_paciente p', 'p.paciente_id = ae.paciente_id', 'left');
            $this->db->where('ae.agenda_exames_id', $agenda_exames_id);
            $return2 = $this->db->get()->result();
//            var_dump($return2); 
//            die;
            
            return $return2;
        }else{
            return false;
        }
    }

    function listar() {

        $this->db->select('empresa_id,
                           email, 

                            nome');
        $this->db->from('tb_empresa');
        $this->db->orderby('empresa_id');
        $return = $this->db->get();
        return $return->result();
    }

}

?>
