<?php
namespace Bot;
use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

include __DIR__.'/../vendor/autoload.php';

class Bot {
  
  private static ?Bot $instance = null;
  private Discord $discord;
  
  private function __construct() {
    $intents = [
      Intents::GUILDS,
      Intents::GUILD_VOICE_STATES,
      Intents::GUILD_EMOJIS_AND_STICKERS,
      Intents::GUILD_MEMBERS,
      Intents::GUILD_INVITES,
      Intents::GUILD_MESSAGES,
      Intents::MESSAGE_CONTENT,
      Intents::GUILD_MESSAGE_REACTIONS,
      // Intents::getDefaultIntents() // Commentato per evitare conflitti con quelli personalizzati
    ];

    // Logger configurato correttamente
    $logger = new Logger('discord');
    $logger->pushHandler(new StreamHandler(__DIR__.'/../logfile.log', \Monolog\Level::Debug));

    // Configurazione del bot Discord
    $this->discord = new Discord([
      'token' => 'MTI5MDc3NTcwMDA2MzkxMTk2Ng.GxLQ6I.RWeIqpKaHm7yOy981CJwxSH4yE_M8oZnu0lIe8',
      'intents' => $intents,
      'loop' => \React\EventLoop\Loop::get(),
      'logger' => $logger,
      'shardId' => 0,
      'shardCount' => 5,
    ]);

    // Configura l'evento per gestire i messaggi
    $this->discord->on('ready', function ($discord) {
      echo "Bot avviato!";
      $discord->on(Event::MESSAGE_CREATE, function ($message) {
        echo "Nuovo messaggio ricevuto: {$message->content}\n";
      });
    });
  }

  public static function getInstance(): Bot {
    if (self::$instance === null) {
      self::$instance = new Bot();
    }
    return self::$instance;
  }

  // Previene clonazione e deserializzazione del singleton
  public function __clone() {}
  public function __wakeup() {}

  public function run() {
    $this->discord->run();
  }
}
