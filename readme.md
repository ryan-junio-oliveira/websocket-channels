
# Biblioteca WebSocket com Suporte a Canais Públicos e Fechados

Esta biblioteca PHP fornece um servidor WebSocket usando a biblioteca Ratchet, permitindo comunicação em **canais públicos** e **canais fechados**. Clientes podem se inscrever em canais específicos ou se comunicar publicamente com todos os outros clientes conectados.

## Instalação

### Pré-requisitos

- PHP 8.2 ou superior
- Composer (para instalação da biblioteca)

### Passos de Instalação

1. Instale a biblioteca via Composer:
   ```bash
   composer require ryan-junio-oliveira/websocket-channels
   ```

2. Após a instalação, crie um arquivo `websocket-server.php` e inicialize o servidor conforme o código de exemplo abaixo.

## Exemplo de Uso

### Servidor WebSocket

Após instalar a biblioteca, você pode rodar um servidor WebSocket utilizando o seguinte código:

```php
require_once('vendor/autoload.php');

$web = new \WebSocketChannels\WebSocketServer();
$web->run();
```

### Executando o Servidor

Execute o servidor com o seguinte comando:
```bash
php websocket-server.php
```

O servidor WebSocket será iniciado na porta `8080`, pronto para aceitar conexões.

## Conexão ao WebSocket no Frontend

Aqui está um exemplo simples de como conectar-se ao servidor WebSocket e enviar mensagens públicas e privadas utilizando JavaScript:

```js
let socket = new WebSocket("ws://localhost:8080");

socket.onopen = function(e) {
    console.log("Conexão estabelecida");

    // Inscrevendo-se em um canal fechado com um ID único
    let channelId = "unique_channel_id";
    socket.send(JSON.stringify({
        action: "subscribe",
        channel_id: channelId
    }));

    // Enviando uma mensagem pública
    socket.send(JSON.stringify({
        message: "Esta é uma mensagem pública"
    }));

    // Enviando uma mensagem para um canal fechado
    socket.send(JSON.stringify({
        channel_id: channelId,
        message: "Mensagem privada para o canal"
    }));
};

socket.onmessage = function(event) {
    console.log("Mensagem recebida: ", JSON.parse(event.data));
};

socket.onclose = function(e) {
    console.log("Conexão encerrada");
};

socket.onerror = function(e) {
    console.log("Erro no WebSocket", e);
};
```

## Como Funciona

### Canais Públicos

Mensagens enviadas sem o campo `channel_id` são consideradas públicas e são transmitidas para todos os clientes conectados.

### Canais Fechados

Mensagens contendo o campo `channel_id` são enviadas apenas para os clientes inscritos nesse canal.

### Exemplo de Envio de Mensagens

1. **Mensagem pública**:
   ```js
   socket.send(JSON.stringify({
       message: "Esta é uma mensagem pública"
   }));
   ```

2. **Mensagem para um canal fechado**:
   ```js
   socket.send(JSON.stringify({
       channel_id: "unique_channel_id",
       message: "Mensagem privada para o canal"
   }));
   ```

## Conclusão

Esta biblioteca oferece um servidor WebSocket simples e eficiente, suportando tanto a comunicação pública quanto privada via canais fechados, permitindo flexibilidade em aplicações de comunicação em tempo real.
