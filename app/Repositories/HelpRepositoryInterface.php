<?php

namespace App\Repositories;

interface HelpRepositoryInterface
{

	public function getHelps($church_id);
	public function createHelp($church_id,$data);
	public function updateHelp($id,$data);
	public function deleteHelp($id);
	public function showHelp($id);
   

}