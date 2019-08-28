<?php 

use Phalcon\Mvc\Controller;

class CitysController extends Controller 
{
	public function indexAction()
	{
		$this->view->citys = Citys::find(array("order" => "name"));
		$this->view->title = "Minha cidades" ;
	}

	// for rendering the new contact form
	public function newAction()
	{
	}

	// for rendering the edit contact form
	public function editAction($id)
	{
		// The following if block is commented because
		// we use dispatcher->forward() method in Update Action
		// and forward method will trigger this check and
		// consider it as an invalid request.
		// So we don't need it in this case.


		// if (!$this->request->isPost()) {

		$city = Citys::findFirst($id);
		if(!$city){
			$this->flash->error("Don’t try be smart and edit an invalid city.");
			$this->dispatcher->forward(['action' => 'index']);
		}
		else {
			$this->tag->displayTo("id", $city->id);
			$this->tag->displayTo("name", $city->name);
			$this->tag->displayTo("uf", $city->uf);
		}

		// }
		// else {
		// 	$this->flash->error("Invalid Request!!!");
		// 	$this->dispatcher->forward(['action' => 'index']);
		// }
	}

	// for creating a new city in db
	public function createAction()
	{
		$city = new Citys();
		$success = $city->save($this->request->getPost(), array('name', 'uf'));

		if ($success) {
			$this->flash->success("Cidade Salvo com Sucesso!");
			$this->dispatcher->forward(['action' => 'index']);
		}
		else {
			$this->flash->error("Algo deu errado! :(  : <br/>");

			foreach ($city->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward(['action' => 'new']);
		}
	}

	// for updating
	public function updateAction()
	{
		if(!$this->request->isPost()){

			$this->flash->error("Requisição Inválida!!!");
		}
		
		else {

			$id = $this->request->getPost("id");
			$city = Citys::findFirst($id);

			if(!$city){
				$this->flash->error("Nenhum registro encontrado");
			}
			else {
				$success = $city->save($this->request->getPost(), array('name', 'uf',));

				if (!$success) {
					$this->flash->error("Following Errors occurred: <br/>");

					foreach ($city->getMessages() as $message) {
						$this->flash->error($message);
					}

					return $this->dispatcher->forward(array(
				        "action" => "edit",
				        "params" => array($city->id)
				    ));
				}

				$this->flash->success("City Successfully Updated!");
			}
		}

		$this->dispatcher->forward(['action' => 'index']);						
	}	

	// for removing a city
	public function deleteAction($id)
	{
		$city = Citys::findFirst($id);

		if(!$city){
			$this->flash->error("Don’t try to remove a city that doesn’t even exist in the first please.");
		}
		else {
			if(!$city->delete()){
				
				foreach ($city->getMessages() as $message) {
					$this->flash->error($message);
				}
			}
			else{
				$this->flash->success("The City R.I.P successful!!!");
			}

		}

		$this->dispatcher->forward(['action' => 'index']);
	}
}