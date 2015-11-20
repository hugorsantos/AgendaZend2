<?php
/**
 * Zend Framework (http://framework.zend.com/)
 * TODO: Classe que controla o crudo de cadastro de usuarios
 * @link      	http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright 	Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   	http://framework.zend.com/license/new-bsd New BSD License
 * @author 		Hugo Reis Santos
 */

namespace Users\Controller;

use Users\Form\UsersForm;
use Users\Model\Users;
use Users\Model\UsersTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UsersController extends AbstractActionController
{
	protected $usersTable;
	
    public function indexAction() {    	
    	return new ViewModel(array(
    		'users' => $this->getUsersTable()->fetchAll(),
    	));
    }	
        
    /**
     * TODO: Metodo que exibe a janela de inclusao de novo registro e acesso o UsersTable para salvar o registro
     * @return \Zend\Http\Response|multitype:\Users\Form\UsersForm
     */
    public function addAction() {
        $form = new UsersForm();
        $request = $this->getRequest();        
        if ($request->isPost()) {
            $users = new Users();
            $form->get('submit')->setAttribute('value', 'Add New User');
            $form->setData($request->getPost());            
            if ($form->isValid()) {
                $users->exchangeArray($form->getData());
                $this->getUsersTable()->saveUsers($users);
                return $this->redirect()->toRoute('users');
            }            
        }
        return array('form' => $form);
    }
    
    /**
     * TODO: Metodo que exibe a tela de edicao de registros
     * @return \Zend\Http\Response|multitype:number \Users\Form\UsersForm
     */
    public function editAction() {
    	/*se nao foi enviado o id do registro ele encaminha para pagina inicial*/
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('users',array('action' => 'index'));
    	}
    	/*consulta os dados no banco*/
    	try {
    		$users = $this->getUsersTable()->getUser($id);    		
    	} catch (\Exception $ex) {
    		return $this->redirect()->toRoute('users', array('action' => 'index'));
    	}
    	/*pega os dados do objeto de preenche o formulario*/
    	$form = new UsersForm();  
    	$form->bind($users);    	
    	$form->get('submit')->setAttribute('value','Edit'); //Update
    	/*retorna os dados do formulario e o id do registro*/
    	return array('id' => $id,'form' => $form,);    	
    }
    
    /**
     * TODO: Metodo que executa a acao de salvar as alteracoes
     * @return \Zend\Http\Response
     */
    public function saveEditAction(){
    	/*cria o objeto de UsersForm*/
    	$form = new UsersForm();
    	/*pega o objeto da requisicao*/
    	$request = $this->getRequest();
    	/*se enviado via post*/    	
    	if ($request->isPost()) {
    		/*pega os dados do formulario*/    		 		
    		$form->setData($request->getPost());
    		/*valida o formulario*/
    		if ($form->isValid()) {
    			/*cria o objeto de Users para preencher o array de objeto*/
    			$users = new Users();
    			/*passa os dados do objeto para o metodo exchangeArray*/
    			$users->exchangeArray($form->getData());    			
    			/*envia para o metodo saveUsers para salvar os registros*/
    			$this->getUsersTable()->saveUsers($users);
    			/*depois de atualizar no banco retorna para a listagem*/
    			return $this->redirect()->toRoute('users');
    		}
    	}
    }
    
    /**
     * TODO: Metodo que exibe a selecao da lista para exclusao
     * @return \Zend\Http\Response|multitype:number NULL
     */
    public function deleteAction() {
    	/*pega o id da selecao da lista*/
    	$id = (int) $this->params()->fromRoute('id',0);
    	/*verifica se existe se nao retorna para listagem*/
    	if (!$id){
    		return $this->redirect()->toRoute('users');
    	}
    	/*recebe o objeto da requisicao*/
    	$request = $this->getRequest();
    	/*verifica se o post e verdadeiro*/
    	if ($request->isPost()){
    		/*verifica se o usuario selecion yes ou no*/
    		$del = $request->getPost('del', 'No');
    		if ($del=='Yes') {
    			/*pega o id da requisicao selecionada na listagem*/
    			$id = (int) $request->getPost('id');
    			/*acessa o metodo deleteUsers para apagar o registro*/
    			$this->getUsersTable()->deleteUsers($id);
    		}
    		/*retorna para a listagem*/
    		return $this->redirect()->toRoute('users');
    	}
    	/*retorna para exibir os dados do usuario se nao foi feito um pedido post*/
    	return array('id'=>$id,'users'=> $this->getUsersTable()->getUser($id));
    }
    
    /**
     * TODO: Metodo que retorna um objeto da classe UsersTable
     * @return Ambigous <object, multitype:>
     */
    public function getUsersTable() {    	
    	if (!$this->usersTable) {
    		$sm = $this->getServiceLocator();
    		$this->usersTable = $sm->get('Users\Model\UsersTable');
    	}    	
    	return $this->usersTable;
    }
}
