# 🚀 Atualização Importante!!

Estamos sempre melhorando! Confira as novas adições:

### 🔧 Configuração do arquivo `.htaccess`

Adicionamos as seguintes regras de segurança e desempenho:

```bash
# Ativar o módulo de reescrita de URL
RewriteEngine On

# Remover /index.php das URLs, mas permitir acesso direto ao index.php
RewriteCond %{THE_REQUEST} /index\.php [NC]
RewriteCond %{REQUEST_URI} !^/index\.php$ [NC]
RewriteRule ^index\.php(.*)$ /$1 [R=301,L]

# Reescrita de URLs amigáveis
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]

# Ativar o módulo de reescrita de URL
<IfModule mod_expires.c>
    ExpiresActive On

    # Definir a expiração para 2 minutos (120 segundos) para diferentes tipos de arquivos
    ExpiresByType image/jpg "access plus 2 minutes"
    ExpiresByType image/jpeg "access plus 2 minutes"
    ExpiresByType image/gif "access plus 2 minutes"
    ExpiresByType image/png "access plus 2 minutes"
    ExpiresByType text/css "access plus 2 minutes"
    ExpiresByType application/javascript "access plus 2 minutes"
    ExpiresByType text/html "access plus 2 minutes"
</IfModule>

# Proteger o arquivo .htaccess
<Files .htaccess>
    Order Allow,Deny
    Deny from all
</Files>

# Proteger arquivos sensíveis
<FilesMatch "\.(env|json|log|ini|sh|bak|sql|yaml)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# Definir uma página de erro personalizada
ErrorDocument 404 /404.html
```

### 🚨 Novidade! Tela personalizada de erro 404

Agora, caso o usuário acesse uma página inexistente, ele verá uma página de erro personalizada 404. Isso melhora a experiência do usuário e ajuda a manter a navegação clara e sem frustrações.

---

### 🔗 Baixe, siga e curta!

Apoie o projeto e fique por dentro de futuras atualizações:

[![Baixar versão 2.0](https://img.shields.io/badge/Download-v2.0-brightgreen)](https://github.com/aglsk/System-Digital/releases/download/2.0/2.0.zip)  
[![Seguir no GitHub](https://img.shields.io/github/followers/aglsk?label=Seguir%20no%20GitHub&style=social)](https://github.com/aglsk)  
[![Curtir o projeto](https://img.shields.io/badge/Curtir-👍-blue)](https://github.com/aglsk/System-Digital)
