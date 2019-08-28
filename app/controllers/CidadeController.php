<?php 

use Phalcon\Mvc\Controller;

class CidadeController extends Controller 
{
	public function indexAction()
	{
		$this->view->cidade = Cidade::find(array("order" => "cidade"));
		$this->view->title = "My Cidade" ;
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

		$cidade = Cidade::findFirst($id);
		if(!$cidade){
			$this->flash->error("Don’t try be smart and edit an invalid cidade.");
			$this->dispatcher->forward(['action' => 'index']);
		}
		else {
			$this->tag->displayTo("id", $cidade->id);
			$this->tag->displayTo("cidade", $cidade->cidade);
			$this->tag->displayTo("UF", $cidade->uf);
		}

		// }
		// else {
		// 	$this->flash->error("Invalid Request!!!");
		// 	$this->dispatcher->forward(['action' => 'index']);
		// }
	}

	// for creating a new contact in db
	public function createAction()
	{
		$cidade = new Cidade();
		$success = $cidade->save($this->request->getPost(), array('cidade', 'uf', ));

		if ($success) {
			$this->flash->success("Cidade Successfully Saved!");
			$this->dispatcher->forward(['action' => 'index']);
		}
		else {
			$this->flash->error("Following Errors occurred: <br/>");

			foreach ($cidade->getMessages() as $message) {
				$this->flash->error($message);
			}

			$this->dispatcher->forward(['action' => 'new']);
		}
	}

	// for updating
	public function updateAction()
	{
		if(!$this->request->isPost()){

			$this->flash->error("Invalid Request!!!");
		}
		
		else {

			$id = $this->request->getPost("id");
            $cidade = Cidades
            ::findFirst($id);

			if(!$cidade){
				$this->flash->error("No such record found");
			}
			else {
				$success = $cidade->save($this->request->getPost(), array('cidade', 'uf'));

				if (!$success) {
					$this->flash->error("Following Errors occurred: <br/>");

					foreach ($cidade->getMessages() as $message) {
						$this->flash->error($message);
					}

					return $this->dispatcher->forward(array(
				        "action" => "edit",
				        "params" => array($cidade->id)
				    ));
				}

				$this->flash->success("Cidade Successfully Updated!");
			}
		}

		$this->dispatcher->forward(['action' => 'index']);						
	}	

	// for removing a contact
	public function deleteAction($id)
	{
		$cidade = Cidade::findFirst($id);

		if(!$cidade){
			$this->flash->error("Don’t try to remove a cidade that doesn’t even exist in the first please.");
		}
		else {
			if(!$cidade->delete()){
				
				foreach ($cidade->getMessages() as $message) {
					$this->flash->error($message);
				}
			}
			else{
				$this->flash->success("The Cidade R.I.P successful!!!");
			}

		}

		$this->dispatcher->forward(['action' => 'index']);
	}
}