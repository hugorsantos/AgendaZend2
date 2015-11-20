<?php
namespace Users\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersTable {
	
	protected $tableGateway;
	
	/**
	 * TODO: Metodo contrutor da calsse ele inicia o objeto $tableGateway  
	 * @param TableGateway $tableGateway
	 */
	public function __construct(TableGateway $tableGateway) {
		$this->tableGateway = $tableGateway;
	}
	
	/**
	 * TODO: Metodo que mostra os dados da listagem, retorna todos os itens
	 * @return \Zend\Db\ResultSet\ResultSet
	 */
	public function fetchAll() {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	/**
	 * TODO: Metodo que pesquisa os dados de um registro selecionado na listagem
	 * @param unknown $id
	 * @throws \Exception
	 * @return Ambigous <multitype:, ArrayObject, NULL>
	 */
	public function getUser($id) {	
		$id  = (int) $id;	
		$rowset = $this->tableGateway->select(array('id' => $id));	
		$row = $rowset->current();	
		if (!$row) {	
			throw new \Exception("Could not find row $id");
		}	
		return $row;
	}
	
	/**
	 * TODO: Metodo utilizado para incluir um novo registro ou atulizar um registro
	 * @param Users $users
	 * @throws \Exception
	 */
	public function saveUsers(Users $users) {
		
		$data = array(
				'name' => $users->name,
				'email' => $users->email,
				'mobile' => $users->mobile,
				'address' => $users->address,
		);	
		$id = (int) $users->id;		
		if ($id == 0) {	
			$this->tableGateway->insert($data);
		} else {	
			if ($this->getUser($id)) {	
				$this->tableGateway->update($data, array('id' => $id));
			} else {
				throw new \Exception('Users id does not exist');
			}
		}
	}
	
	/**
	 * TODO: Metodo utilizado para excluir um registro no banco de dados
	 * @param Integer $id
	 */
	public function deleteUsers($id)
	{
		$this->tableGateway->delete(array('id' => (int) $id));
	}
	
}