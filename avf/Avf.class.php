<?php
/**
 * Api View framework (AV视图框架).
 * Author: monkey<my455628442@gmail.com>
 * Date: 2015/7/16 0016
 * Time: 17:39
 * File Using:Avf 框架系统引导类
 */
namespace avf;

class Avf {

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    static public function load() {
        // 注册AUTOLOAD方法
        spl_autoload_register('avf\Avf::autoload');
        // 设定错误和异常处理
        register_shutdown_function('avf\Avf::fatalError');
        set_error_handler('avf\Avf::appError');
        set_exception_handler('avf\Avf::appException');

        // 引导加载系统文件
        $load = include AVF_PATH . "load.php";
        // 加载核心文件
        foreach ($load['core'] as $file){
            if(is_file($file)) {
                include $file;
            }
        }
        // 加载应用模式配置文件
        foreach ($load['config'] as $key=>$file){
            is_numeric($key)?C(load_config($file)): C($key,load_config($file));
        }
        Route::dispose_url();


    }

    /**
     * 类库自动加载
     * @param string $class 对象类名
     * @return void
     */
    public static function autoload($class) {
        $path = AVF_PATH;
        $filename = $path . str_replace('\\', '/', $class) . EXT;
        if(is_file($filename)) {
            include $filename;
        } else {
            $filename = $path . __NAMESPACE__ . str_replace('\\', '/', $class) . EXT;
            include $filename;
        }
    }

    /**
     * 自定义异常处理
     * @access public
     * @param mixed $e 异常对象
     */

    static public function appException($e) {
        $error = array();
        $error['message']   =   $e->getMessage();
        $trace              =   $e->getTrace();
        if('E'==$trace[0]['function']) {
            $error['file']  =   $trace[0]['file'];
            $error['line']  =   $trace[0]['line'];
        }else{
            $error['file']  =   $e->getFile();
            $error['line']  =   $e->getLine();
        }
        $error['trace']     =   $e->getTraceAsString();
        // 发送404信息
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        //TODO 记录日志
        self::dieErr($error);
    }

    /**
     * 自定义错误处理
     * @access public
     * @param int $errno 错误类型
     * @param string $errstr 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     * @return void
     */

    static public function appError($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $error['message']  =   $errstr;
                $error['file']  =   $errfile;
                $error['line']  =   $errline;
                //TODO 记录日志
                self::dieErr($error);
                break;
            default:
                $error['message']  =   $errstr;
                $error['file']  =   $errfile;
                $error['line']  =   $errline;
                //TODO 记录日志
                self::dieErr($error);
                break;
        }
    }

    /**
     * fatalError
     * 致命错误处理函数
     * @static
     * @access public
     * @return void
     */
    static public function fatalError() {
        //TODO 记录日志
        if ($e = error_get_last()) {
            switch($e['type']){
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    self::dieErr($e);
                    break;
            }
        }
    }

    /**
     * 错误输出
     * @param mixed $error 错误
     * @return void
     */
    static public function dieErr($error) {
        $e = array();
        if (API_DEBUG) {
            //调试模式下输出错误信息
            if (!is_array($error)) {
                $trace          = debug_backtrace();
                $e['message']   = $error;
                $e['file']      = $trace[0]['file'];
                $e['line']      = $trace[0]['line'];
                ob_start();
                debug_print_backtrace();
                $e['trace']     = ob_get_clean();
            } else {
                $e              = $error;
            }
        } else {
            $e['status'] = 1000;
            $e['data'] = array();
            $e['message'] = 'FatalError';
        }
        die(json_encode($e));
    }
}