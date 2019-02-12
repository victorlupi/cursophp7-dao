<?php 

class Usuario 
{
	private $id_usuario;
	private $des_login;
	private $des_senha;
	private $dt_cadastro;

	public function getId_usuario(){
		return $this->id_usuario;
	}

	public function setId_usuario($value)
	{
		$this->id_usuario = $value;
	}

	public function getDes_login(){
		return $this->des_login;
	}

	public function setDes_login($value)
	{
		$this->des_login = $value;
	}

	public function getDes_senha()
	{
		return $this->des_senha;
	}

	public function setDes_senha($value)
	{
		$this->des_senha = $value;
	}

	public function getDt_cadastro()
	{
		return $this->dt_cadastro;
	}

	public function setDt_cadastro($value)
	{
		$this->dt_cadastro = $value;
	}
	
	public function loadById($id)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_usuarios WHERE id_usuario = :ID", array(
			":ID"=>$id
		));
		if (count($results) > 0) 
		{
			$this->setData($results[0]);
		}
	}
	public static function getList()
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_usuarios ORDER BY des_login;");
	}
	public static function search($login)
	{
		$sql = new Sql();
		return $sql->select("SELECT * FROM tb_usuarios WHERE des_login LIKE :SEARCH ORDER BY des_login", array(
			':SEARCH'=>"%".$login."%"
		));
	}
	public function login($login, $password)
	{
		$sql = new Sql();
		$results = $sql->select("SELECT * FROM tb_usuarios WHERE des_login = :LOGIN AND des_senha = :PASSWORD", array(
			":LOGIN"=>$login,
			":PASSWORD"=>$password
		));
		if (count($results) > 0) {
			$this->setData($results[0]);
		} else {
			throw new Exception("Login e/ou senha inválidos.");
		}
	}
	public function setData($data){
		$this->setId_usuario($data['id_usuario']);
		$this->setDes_login($data['des_login']);
		$this->setDes_senha($data['des_senha']);
		$this->setDt_cadastro(new DateTime($data['dt_cadastro']));
	}
	public function insert(){
		$sql = new Sql();
		$results = $sql->select("CALL sp_usuarios_insert(:LOGIN, :PASSWORD)", array(
			':LOGIN'=>$this->getDes_login(),
			':PASSWORD'=>$this->getDes_senha()
		));
		if (count($results) > 0) {
			$this->setData($results[0]);
		}
	}
	public function update($login, $password){
		$this->setDes_login($login);
		$this->setDes_senha($password);
		$sql = new Sql();
		$sql->query("UPDATE tb_usuarios SET des_login = :LOGIN, des_senha = :PASSWORD WHERE id_usuario = :ID", array(
			':LOGIN'=>$this->getDes_login(),
			':PASSWORD'=>$this->getDes_senha(),
			':ID'=>$this->getId_usuario()
		));
	}
	public function delete(){
		$sql = new Sql();
		$sql->query("DELETE FROM tb_usuarios WHERE id_usuario = :ID", array(
			':ID'=>$this->getId_usuario()
		));
		$this->setId_usuario(0);
		$this->setDes_login("");
		$this->setDes_senha("");
		$this->setDt_cadastro(new DateTime());
	}
	public function __construct($login = "", $password = ""){
		$this->setDes_login($login);
		$this->setDes_senha($password);
	}
	public function __toString(){
		return json_encode(array(
			"id_usuario"=>$this->getId_usuario(),
			"des_login"=>$this->getDes_login(),
			"des_senha"=>$this->getDes_senha(),
			"dt_cadastro"=>$this->getDt_cadastro()->format("d/m/Y H:i:s")
		));
	}
} 	
	
 ?>