<?php
/**
 * Файл класса AmoCRM.php
 *
 * @author Samsonov Vladimir <vs@chulakov.ru>
 * @copyright Copyright (c) 2018, Oleg Chulakov Studio
 * @link http://chulakov.com/
 */

namespace chulakov\yii\amocrm;

use yii\base\Configurable;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\helpers\Inflector;
use Chulakov\AmoCRM\Auth\BasicAuth;
use Chulakov\AmoCRM\AuthInterface;
use Chulakov\AmoCRM\Client\DefaultClient;
use Chulakov\AmoCRM\ClientInterface;
use Chulakov\AmoCRM\EntityInterface;

/**
 * Обертка для работы с пакетом OlegChulakovStudio/amocrm
 */
class AmoCRM implements Configurable
{

    /**
     * @var string
     */
    public $subdomain;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var AuthInterface
     */
    protected $auth;

    /**
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct($config = [])
    {
        if (!empty($config)) {
            \Yii::configure($this, $config);
        }
        $this->init();
    }

    /**
     * Возвращает текущего клиента для работы с API или инициализирует клиента по умолчанию
     * @return DefaultClient|ClientInterface
     */
    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new DefaultClient($this->subdomain);
        }

        return $this->client;
    }

    /**
     * Устанавливает кастомного клиента для работы с API
     * @param ClientInterface $client
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Возвращает объект с авторизационными данными, необходимыми для запроса
     * @return BasicAuth|AuthInterface
     */
    public function getAuth()
    {
        if (is_null($this->auth)) {
            $this->auth = new BasicAuth(
                $this->login,
                $this->hash
            );
        }
        return $this->auth;
    }

    /**
     * Устанавливает кастомный объект с авторизационными данными
     * @param AuthInterface $auth
     */
    public function setAuth(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Выполняем проверку наличия необходимых параметров
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!$this->subdomain) {
            throw new InvalidConfigException("Необходимо задать 'subdomain'");
        }

        if (!$this->login) {
            throw new InvalidConfigException("Необходимо задать 'login'");
        }

        if (!$this->hash) {
            throw new InvalidConfigException("Необходимо задать 'hash'");
        }
    }

    /**
     * Магический метод производит подбор и возвращает запрашиваемую сущность API
     * @param string $name
     * @return EntityInterface
     * @throws UnknownClassException
     */
    public function __get($name)
    {
        $name = Inflector::camelize($name);

        $entityClass = "\\Chulakov\\AmoCRM\\Entity\\{$name}Entity";

        if (class_exists($entityClass)) {
            /** @var EntityInterface $entity */
            $entity = new $entityClass();

            $entity->setClient($this->getClient());
            $entity->setAuth($this->getAuth());

            return $entity;
        }

        throw new UnknownClassException("Сущность $entityClass не определена");
    }
}