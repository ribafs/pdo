<?php

/* Classe com funções úteis em geral */

Class Util
{
    // Receber as permissões de arquivos ou pastas no formato 755, por exemplo
    public function getFilePerms($file) {
            $length = strlen(decoct(fileperms($file)))-3;
            return substr(decoct(fileperms($file)),$length);
    }

    // Copiar uma pasta com todos os arquivos e subpastas recursivamente
    // Caso a pasta de destino não exista será criada
    // copyDir('j381/installation', 'joomla3/installation');
    // Crédito - https://stackoverflow.com/questions/2050859/copy-entire-contents-of-a-directory-to-another-using-php#2050909
    function copyDir($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else {
                    $permsApp = substr($this->getFilePerms($dst),0,1);
                    if($permsApp == '7' || $permsApp == '6'){
                        copy($src . '/' . $file,$dst . '/' . $file); 
                    }else{
                        print 'O diretório "'.$dst . '" tem permissão de ' . $this->getFilePerms($dst) . '. Requer permissão de escrita para o Apache';
                        exit;
                    }
                } 
            } 
        } 
        closedir($dir); 
    } 
}
