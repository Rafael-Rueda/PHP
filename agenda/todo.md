    Quando o usuário faz login com sucesso, você gera um token de autenticação único para esse usuário e o armazena no banco de dados, associado à conta do usuário.

    Além disso, você também armazena esse token na sessão do usuário. Isso permite que o usuário permaneça autenticado enquanto a sessão estiver ativa.

    Ao tentar acessar uma página protegida, você verifica se o token na sessão do usuário corresponde ao token armazenado no banco de dados para essa conta específica.