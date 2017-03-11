<?php
echo Json::encode([
	"ekitoho"        => (new Ekitoho())->findAll(),
	"kodawari_joken" => (new KodawariJoken())->findAll(),
	"madori"         => (new Madori())->findAll(),
	"menseki"        => (new Menseki())->findAll(),
	"rosen"          => (new Rosen())->findAll(),
	"station"        => (new Station())->findAll(),
	"tikunensu"      => (new Tikunensu())->findAll(),
	"yatin"          => (new Yatin())->findAll(),
]);