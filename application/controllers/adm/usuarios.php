<?php

class Usuarios extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        if(!$this->autenticacao->verifica_acesso()){
            
            redirect('adm/login');
        }
        
         $this->load->model(array(
            'adm/usuario_model',
            'adm/grupo_model'
        ));
         
         $this->load->config('usuarios');
        
        $this->load->library('form_validation');
        
    }
    
    function index(){
        
        
        $dados['usuarios'] = $this->usuario_model->get_all();
        $dados['view']     = 'adm/usuarios/index';
        $dados['titulo']   = 'Gerenciar usuários';
        $dados['css'][]    = 'jquery-ui.blue';
        $dados['js'][]     = 'data/jquery-ui';
        $dados['js'][]     = 'jquery.mask.min';
        $dados['js'][]     = 'usu.init';
        
        $this->load->view('/layout',$dados);
    }
    
    function cadastrar(){
        
        $dados['grupos'] = $this->grupo_model->get_all();
        
        $dados['titulo'] = 'Cadastrar usuário';
        $dados['view']   = 'adm/usuarios/editar';
        $dados['js'][]   = 'plugins/jquery.validate';
        $dados['css'][]  = 'jquery-ui.blue';
        $dados['js'][]   = 'data/jquery-ui';
        $dados['js'][]   = 'jquery.mask.min';
        $dados['js'][]   = 'usu.init';
        
        $this->load->view('/layout',$dados);
        
    }
    
    function editar($id = NULL){
        
        if($id == NULL){
            redirect('adm/usuarios', 'refresh');
        }
        
        $dados['usuario'] = $this->usuario_model->get_by_id($id);
        if(empty($dados['usuario'])){
            redirect('adm/usuarios', 'refresh');
        }        
        $dados['grupos']  = $this->grupo_model->get_all();
        
        $dados['titulo'] = 'Editar usuário';
        $dados['view']   = 'adm/usuarios/editar';        
        $dados['js'][]   = 'plugins/jquery.validate';
        $dados['js'][]   = 'jquery.mask.min';
        $dados['css'][]  = 'jquery-ui.blue';
        $dados['js'][]   = 'data/jquery-ui';
        $dados['js'][]   = 'usu.init';
        
        
        $this->load->view('/layout',$dados);
    }
    
    ///*
    //@Parametro: NULL
    //@Descrição: Valida e chama a model para salvar os dados de cadastro de um usuario
    //@Retorno: NULL
    //*/
    function salvar(){
        
         // Busca as regras de validacao nos arquivos de configuracao
        $regras = $this->config->item('regras_validacao');
        
        // Seta as regras na library de validacao
        $this->form_validation->set_rules($regras);
        
        // Seta o html das mensagens de validacao
        $this->form_validation->set_error_delimiters('<label class="control-label" for="inputError">', '</label>');
        
        
        $usuario = new stdClass();

        $id                                     = $this->input->post('id');
        $usuario->usu_nome                      = $this->input->post('nome');
        $usuario->usu_telefone                  = $this->input->post('telefone');
        $usuario->usu_matricula                 = $this->input->post('matricula');
        $usuario->usu_email                     = $this->input->post('email');
        $usuario->usu_status                    = $this->input->post('status');
        $usuario->usu_data_nascimento           = $this->input->post('data_nascimento');
        $usuario->usu_sexo                      = $this->input->post('sexo');
        
        $usuario->grupos_gru_id         = $this->input->post('grupos');
        
        $senha = $this->input->post('senha');
        
        
        
        if ($this->form_validation->run() === FALSE) {            
            // Caso os dados sejam invalidos exibe o formulario de validacao novamente
            
            $usuario->id = $id;
            $dados['usuario'] = $usuario; 
            $dados['grupos']  = $this->grupo_model->get_all();
            
            $dados['titulo'] = 'Editar usuário';
            $dados['view']   = 'adm/usuarios/editar';
            $dados['js'][]   = 'pages/editar_usuario';
            
            $this->load->view('/layout',$dados);
        } else {
            
            // Se foi informada a senha do usuario criptografa ela
            if (!empty($senha)) {
                $this->load->helper('security');
                $senha = do_hash($senha, 'MD5');

                $usuario->usu_senha = $senha;
            } else {
                if (empty($id)) {
                    $mensagem = array('msg' => 'erro', 'tipo' => 'danger');

                    $this->session->set_flashdata('msg', $mensagem);

                    redirect('adm/usuarios', 'refresh');
                }
            }

        // Verifica se deve atualizar ou inserir o registro
            
            if (empty($id)) {
                // Caso nao seja informado o ID do registro a ser atualizado insere um novo
                $resultado = $this->usuario_model->inserir($usuario);
            } else {
               
                $usuario->usu_id = $id;
                $resultado = $this->usuario_model->atualizar($usuario);
            }

            // Captura o resultado da operacao e seta a mensagem a ser exibida para o usuario
            if ($resultado) {

                if (empty($id)) {

                    $mensagem = array('msg' => 'insert-ok', 'tipo' => 'success');
                } else {

                    $mensagem = array('msg' => 'update-ok', 'tipo' => 'info');
                }
            } else {
                $mensagem = array('msg' => 'erro', 'tipo' => 'danger');
            }

            // Grava a mensagem numa flashdata
            $this->session->set_flashdata('msg', $mensagem);

            // Redireciona o usuario para a tela de gerenciamento
            redirect('adm/usuarios', 'refresh');
        }
    }
    
    function remover($id){
        
        // informa o banco de dados qual registro deve ser removido
        $resultado = $this->usuario_model->remover($id);
        
        // Captura o resultado da operacao
        if($resultado){
            
            $mensagem = array('msg' =>'delete-ok', 'tipo'=> 'success');
        }
        else{
            $mensagem = array('msg' =>'erro', 'tipo'=> 'danger');
        }
        
        // Seta a mensagem numa flashdata
        $this->session->set_flashdata('msg',$mensagem);
        
        //Redireciona para a tela de gerenciamento
        redirect('adm/usuarios', 'refresh');
    }
}
