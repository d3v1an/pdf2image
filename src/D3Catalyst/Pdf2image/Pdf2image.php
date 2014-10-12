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
                WHERE Fecha = CURDATE() AND idCapturista NOT IN(28) AND (n.Periodico=p.idPeriodico)
                UNION ALL
                SELECT CONCAT('/var/www/siscap.la/public/Periodicos/',p.Nombre,'/',n.Fecha,'/') AS 'ruta', 'anuncios' AS 'tipo'
                FROM anunciosDia n, periodicos p
                WHERE Fecha = CURDATE() AND idCapturista NOT IN(28) AND (n.Periodico=p.idPeriodico)
            ) AS t1";

		$pdfs 	= \DB::select( \DB::raw( $query ) );

		if(count($pdfs)>0) {
			foreach ($pdfs as $dir) {

				// Verificamos si existe el TODOS_1.pdf y lo elinamos
				if(!\File::exists($dir->rutas."TODAS_1.pdf")) \File::delete($dir->rutas."TODAS_1.pdf");

				// Verificamos si existe el TODOS_1.pdf y lo elinamos
				if(!\File::exists($dir->rutas."A.pdf")) \File::delete($dir->rutas."A.pdf");

				// Listamos los pdf's del directorio actual
				$pdf_files = \File::files($dir->rutas);

				// Recorremos los archivos, si no existe lo creamos
				for ($i=0; $i < count($pdf_files); $i++) { 
					if(!\File::exists($pdf_files[$i].".jpg")) {
						// Convertimos el pdf
						$command = 'convert -verbose -density 170 -trim "' . $pdf_files[$i] . '" -quality 50 -sharpen 0x1.0 ' . '"' . $pdf_files[$i] . '.jpg"';
						exec($command,$arr);
						pre($arr);
					}
				}

			}
		}

		$this->line("Done comando ejecutado!!");

	}
}
