<?php namespace D3Catalyst\Pdf2image;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Pdf2image extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'utils:pdf2image';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Genera la la conversion entre pdf e imagenes';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$query	= "SELECT DISTINCT(ruta) rutas
            FROM 
            (
                SELECT CONCAT('/var/www/siscap.la/public/Periodicos/',p.Nombre,'/',n.Fecha,'/') AS 'ruta', 'noticias' AS 'tipo'
                FROM noticiasDia n, periodicos p
                WHERE idCapturista NOT IN(28) AND (n.Periodico=p.idPeriodico)
                UNION ALL
                SELECT CONCAT('/var/www/siscap.la/public/Periodicos/',p.Nombre,'/',n.Fecha,'/') AS 'ruta', 'anuncios' AS 'tipo'
                FROM anunciosDia n, periodicos p
                WHERE idCapturista NOT IN(28) AND (n.Periodico=p.idPeriodico)
            ) AS t1";

		$pdfs 	= \DB::select( \DB::raw( $query ) );

		if(count($pdfs)>0) {
			foreach ($pdfs as $dir) {
				exec("ls -lah '{$dir->rutas}'",$arr);
				pre($arr);
			}
		}

		$this->line("Done comando ejecutado!!");

	}
}
