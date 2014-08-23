<?php
namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Database config generator
 * @author Igor Belka <work.belka@gmail.com>
 * @since 2.0
 */
class DbController extends Controller
{
    
    /**
     * @var string
     */
    public $host = 'localhost';
    
    /**
     * @var string
     */
    public $user = 'root';
    
    /**
     * @var string
     */
    public $pass = '';
    
    /**
     * @var string
     */
    public $dbname;
    
    /**
     * @var string
     */
    public $charset = 'utf8';
    
    /**
     * @var string
     */
    public $driver = 'mysql';
    
    /**
     * @var int
     */
    public $port = 3306;
    
    /**
     * @var string
     */
    public $defaultAction = 'generate';
    
    /**
     * @return array
     */
    public function options($actionId) {
        return ['dbname', 'host', 'user', 'pass', 'charset', 'driver'];
    }
    
    /**
     * @return bool
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            echo "Database config generator by Igor Belikov <work.belka@gmail.com>\n";
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Generate a config file
     * @return int
     */
    public function actionGenerate() {
        if ($this->dbname === null) {
            $this->dbname = $this->prompt('DBNAME', ['required' => true]);
        }
        $config = ['class' => 'yii\db\Connection', 'dsn' => $this->driver . ':host=' . $this->host . ';dbname=' . $this->dbname . ';port=' . $this->port, 'username' => $this->user, 'password' => $this->pass, 'charset' => $this->charset, ];
        $code = $this->phpAsString($this->returnAsString($this->arrayAsString($config)));
        $this->createConfig($code);
        echo "Configuration successfully created.\n";
        return Controller::EXIT_CODE_NORMAL;
    }
    
    /**
     * @param string $code
     * @param string $config
     */
    private function createConfig($code, $config = 'db') {
        $file = fopen(Yii::getAlias('@app') . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . $config . '.php', 'w+');
        fwrite($file, $code);
        fclose($file);
    }
    
    /**
     * @param string $code
     * @return string
     */
    private function phpAsString($code, $close = false) {
        return "<?php\n" . $code . ($close ? "\n?>" : '');
    }
    
    /**
     * @param string $code
     * @return string
     */
    private function returnAsString($code) {
        return 'return ' . $code . ';';
    }
    
    /**
     * @param array $data
     * @return string
     */
    private function arrayAsString(array $data) {
        $str = 'array(' . "\n";
        foreach ($data as $key => $value) {
            $str.= "\t'" . $key . '\' => \'' . $value . "',\n";
        }
        $str.= ')';
        return $str;
    }
}