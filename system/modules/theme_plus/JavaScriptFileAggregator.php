<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class JavaScriptFileAggregator
 * 
 * 
 * @copyright  InfinitySoft 2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Layout Additional Sources
 */
class JavaScriptFileAggregator extends ThemePlusFile {
	
	/**
	 * The files to aggregate.
	 */
	protected $arrFiles;
	
	
	/**
	 * The aggregated file.
	 */
	protected $strAggregatedFile;
	
	
	/**
	 * Create a new css file object.
	 */
	public function __construct()
	{
		$this->arrFiles = func_get_args();
		$this->strAggregatedFile = null;
	}
	
	
	/**
	 * Add a file.
	 */
	public function add(LocalJavaScriptFile $objFile)
	{
		$this->arrFiles[] = $objFile;
	}

	
	public function getFile()
	{
		if ($this->strAggregatedFile == null)
		{
			$arrFiles = array();
			$strKey = count($this->arrFiles);
			foreach ($this->arrFiles as $objThemePlusFile)
			{
				if ($objThemePlusFile instanceof LocalJavaScriptFile)
				{
					if ($objThemePlusFile->isAggregateable())
					{
						$strFile = $objThemePlusFile->getFile();
						$objFile = new File($strFile);
						$arrFiles[] = $strFile;
						$strKey .= sprintf(':%s-%d', basename($strFile, '.css'), $objFile->mtime);
						continue;
					}
				}
				throw new Exception('Could not aggreagate the file: ' . $objFile);
			}
			
			$strTemp = 'system/html/javascript-' . substr(md5($strKey), 0, 8) . '.css';
			
			if (!file_exists(TL_ROOT . '/' . $strTemp))
			{
				$this->import('Compression');
				
				// import the gzip compressor
				$strGzipCompressorClass = $this->Compression->getCompressorClass('gzip');
				$this->import($strGzipCompressorClass, 'Compressor');
				
				// build the content
				$strContent = '';
				
				foreach ($arrFiles as $strFile)
				{
					$objFile = new File($strFile);
					
					// get the css code
					$strSubContent = $objFile->getContent();
					
					// detect and decompress gziped content
					$strSubContent = ThemePlus::decompressGzip($strSubContent);
					
					// append to content
					$strContent .= "\n" . $strSubContent;
				}
				
				// write the file
				$objTemp = new File($strTemp);
				$objTemp->write($strContent);
				$objTemp->close();
				
				// create the gzip compressed version
				if (!$GLOBALS['TL_CONFIG']['theme_plus_gz_compression_disabled'])
				{
					$this->Compressor->compress($strTemp, $strTemp . '.gz');
				}
			}
			
			$this->strAggregatedFile = $strTemp;
		}
		return $this->strAggregatedFile;
	}
	
	
	/**
	 * Get embeded html code
	 */
	public function getEmbededHtml()
	{
		// get the file
		$strFile = $this->getFile();
		$objFile = new File($strFile);
		
		// get the css code
		$strContent = $objFile->getContent();
		
		// return html code
		return '<script type="text/javascript">' . $strContent . '</script>';
	}
	
	
	/**
	 * Get included html code
	 */
	public function getIncludeHtml()
	{
		// get the file
		$strFile = $this->getFile();
		
		// return html code
		return '<script type="text/javascript" src="' . specialchars($strFile) . '"></script>';
	}
}

?>