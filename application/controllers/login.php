<?php

class Login extends Controller {

    function Login() {
        parent::Controller();
        $this->load->model('login_model', 'login');
        $this->load->model('ambulatorio/empresa_model', 'empresa');
        $this->load->library('mensagem');
    }

    function index() {
        $this->carregarView();
    }

    function esqueciminhasenha() {

        $this->load->view('login-senha');
    }

    function criarsenha() {
        $usuario = $_POST['txtCPF'];
        $email = $_POST['txtemail'];
        $empresa = $this->login->listar();
//        var_dump($empresa);
//        var_dump($email);
//        die;
        $resposta = $this->login->autenticarcpfemail($usuario, $email);

        if ($resposta != false) {
            $nome = $resposta[0]->paciente;
            $senha = $resposta[0]->agenda_exames_id;
            $mensagem = "Nome: $nome " . "CPF: $usuario " . "Senha : $senha ";
//            var_dump($mensagem);
//            die;
            $this->load->library('email');

            $config['protocol'] = 'smtp';
            $config['smtp_host'] = 'ssl://smtp.gmail.com';
            $config['smtp_port'] = '465';
            $config['smtp_user'] = 'equipe2016gcjh@gmail.com';
            $config['smtp_pass'] = 'DUCOCOFRUTOPCE';
            $config['validate'] = TRUE;
            $config['mailtype'] = 'html';
            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";

            $this->email->initialize($config);
            if ($empresa[0]->email != '') {
                $this->email->from($empresa[0]->email, $empresa[0]->nome);
            } else {
                $this->email->from('equipe2016gcjh@gmail.com', 'STG - CLIENTE');
            }

            $this->email->to($email);
            $this->email->subject("Usuário e senha");
            $this->email->message($mensagem);
            if ($this->email->send()) {
                $data['mensagem'] = "Email enviado com sucesso.";
            } else {
                $data['mensagem'] = "Envio de Email malsucedido.";
            }
        } else {
            $data['mensagem'] = "CPF ou email não cadastrados no sistema.";
        }


//        $data['mensagem'] = $this->mensagem->getMensagem('Navegador n&atilde;o suportado. Utilize o Firefox.');
        $this->carregarView($data);
    }

    function autenticar() {
        $usuario = $_POST['txtLogin'];
        $senha = $_POST['txtSenha'];

//        var_dump($usuario);
//        var_dump($senha);
//        die;

        //Pegando o nome e versao do navegador
        preg_match('/Firefox.+/', $_SERVER['HTTP_USER_AGENT'], $browserPC);
        preg_match('/FxiOS.+/', $_SERVER['HTTP_USER_AGENT'], $browserIOS);
        $teste1 = count($browserPC);
        $teste2 = count($browserIOS);

        if ($teste1 > 0 || $teste2 > 0 || true) {
            //Pegando somente o numero da versao.
            preg_match('/[0-9].+/', $browserPC[0], $verificanavegador['versao']);

            if (($this->login->autenticar($usuario, $senha)) &&
                    ($this->session->userdata('autenticado') == true)) {
                $valuecalculado = 0;
                setcookie("TestCookie", $valuecalculado);
                redirect(base_url() . "home", "refresh");
            } else {
                $data['mensagem'] = $this->mensagem->getMensagem('login002');
                $this->carregarView($data);
            }
        } else {
            $data['mensagem'] = $this->mensagem->getMensagem('Navegador n&atilde;o suportado. Utilize o Firefox.');
            $this->carregarView($data);
        }
    }

    function sair() {
        $this->session->sess_destroy();
        $data['mensagem'] = $this->mensagem->getMensagem('login003');
        $this->carregarView($data);
    }

    private function carregarView($data = null, $view = null) {
        if (!isset($data)) {
            $data['mensagem'] = '';
        }
        $data['empresa'] = $this->login->listar();
        $this->load->view('login', $data);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
