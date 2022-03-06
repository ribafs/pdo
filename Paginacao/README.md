# Pagnação passo a passo

## Com bons recursos

- CRUD
- PDO
- BootStrap
- Busca

## Começar baixando os assets atuais:

Ciar a pasta
pagination

Criar
```php
pagination
	assets
		css
		js
```
- Bootstrap - https://getbootstrap.com/

Baixe a versão 3 do BootStrap pois o bootpag não funciona ainda com o 4.

Descompactar e Copiar bootstrap.min.css par assets/css

Copiar bootstrap.min.js para assets/js

jQuery - https://code.jquery.com/jquery-3.5.1.min.js

Baixar e copiar para assets/js

Baixar o bootpag

https://botmonster.com/jquery-bootpag/

Copiar para assets/js

## Agora copie a estrutura de arquivos php

db_connect.php
fetch_data.php
index.php
search.php

Ajuste o path no index.php e no search.php

Aqui também passo um pequeno script sql para testes.

Crédito - a estrutura básica baixei do site
https://www.kodingmadesimple.com/2017/01/simple-ajax-pagination-in-jquery-php-pdo-mysql.html

E como gostei tenho usado esta paginação em meus aplicativos.

Agora para criar um CRUD não dará trabalho.

- Criar um botão para incluir acima que chama o script insert.php
- Criar dois botões na listagem, um para editar e outro para excluir, que chamam os respectivos scrcipt. Se quizer algo pronto:
https://github.com/ribafs/aplicativos-php


