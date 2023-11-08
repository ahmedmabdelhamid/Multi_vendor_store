<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
class addUser extends Command
{

    protected $progressBar;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AddUser  {type : Type}
    {--timeout=300} : How many seconds to allow each process to run.
    {--debug} : Show process output or not. Useful for debugging.
    {--force} : set force replace
    {--N|name= : The name of the new user}
                            {--E|email= : The user\'s email address}
                            {--P|password= : User\'s password}
                            {--encrypt=true : Encrypt user\'s password if it\'s plain text ( true by default )}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    public $sourcePath='';
    public $force=false;
    protected $description = 'publish auth Guards files data for Amer';

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        dd(config('auth'));
        $options = new OutputFormatterStyle('red', '#ff0', ['bold', 'blink','underscore']);
        $underscore = new OutputFormatterStyle(null, null, ['bold', 'blink','underscore']);
        $output->getFormatter()->setStyle('underscore', $underscore);
        $output->getFormatter()->setStyle('options', $options);
        $io = new SymfonyStyle($input, $output);
        //$this->box('Welcome to Amer Installer');
            if ( ! $input->getArgument('type')) {
                $input->setArgument(
                    'type',
                    $io->choice('Are you sure to install Options?',['yes','no'])
                );
            }
    }
    public function __construct(){
        $this->sourcePath=config('amer.package_path');
        parent::__construct();
    }
    public function handle()
    {
        if($this->input->getArgument('type') == 'no'){exit();}
        if($this->option('force')){$this->force='--force';}
        $mainsteps=10;
            $this->progressBar = $this->output->createProgressBar($mainsteps);
        $this->progressBar->setEmptyBarCharacter('<comment>=</comment>');
        $this->progressBar->setBarWidth(100);
        $this->progressBar->setFormat('verbose');
        $this->progressBar->minSecondsBetweenRedraws(0);
        $this->progressBar->maxSecondsBetweenRedraws(120);
        $this->progressBar->setRedrawFrequency(1);
        /////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////
                /////////
                ////////////
                ////////////////////////////////

            /////////////////////////////////////////
            $this->line('');
            $this->info('User Area');
            $this->progressBar->advance();
            $this->usertable();

    }
    public function askmultilines($question,array $lines){
        $this->asks($question);
        foreach($lines as $line){
            $this->line("   <fg=blue>".$line."</>");
        }
        return $this->ask('');
    }
    public function asks(string $question){
        $this->line("<fg=red>".$question."</>");
    }
    function usertable(){
        $auth = \App\Models\Admin::class;
        $user = new $auth();
        $this->line('');
        $this->info('Creating a new user');
        $loghint=[
            '<bg=red>Attention!',
            '*<underscore>dont start with number or any sign</>','*<underscore>do not use space or any sign</>',
            '*<underscore>allowed sign "_"</>','*<underscore>model name must length more than 6 letters</>'];
        $username=$this->askmultilines('    <options>enter user name</>',$loghint);
        $username=trim($username);
        if(($username == '') || (is_numeric($username)) || (is_null($username)) || (strlen($username)<6)){
            $this->line("Please Insert Right username");
            exit();
        }
        if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $username))
        {
            $this->line('The username has symbol!');return;
        }

        if(Str::isAscii($username) == false){$this->errorBlock('The class has symbol!');exit();}
        $lognamehint=['enter right name'];
        $name=$this->askmultilines('enter your name',$lognamehint);
        $logmobilehint=['enter right mobile'];
        $mobile=$this->askmultilines('enter your mobile',$logmobilehint);

        $loghint=['enter right email'];
        $email=$this->askmultilines('enter your Email',$loghint);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errorBlock('Please Enter Valid Email');exit();
          }
          if (! $password = $this->option('password')) {
            $password = $this->secret('Password');
        }
        if ($this->option('encrypt')) {
            $password = bcrypt($password);
        }

        if(count($user->where('email',$email)->get())){
            $this->info('user already exists');
        }else{
            $req=$user->get()->toArray();
            $lastid=Arr::last(Arr::sort(Arr::map($req, function ($value, $key) {
                return $value['id'];
            })));
            $user->id=$lastid+1;
            $user->name = $name;
            $user->username = $username;
            $user->phone_number = $mobile;
            $user->email = $email;
            $user->password = $password;
            if ($user->save()) {
                $this->info('Successfully created new user');
            } else {
                $this->error('Something went wrong trying to save your user');
            }
        }
    }
    public function checkservice(){
        $return=[];

        $als=[
        'AmerHendy/Security'=>$this->get_loaded_providers('Amerhendy\Security\AmerSecurityServiceProvider'),
        'laravel/ui'=>$this->get_loaded_providers('Laravel\Ui\UiServiceProvider'),
        'spatie/laravel-permission'=>$this->get_loaded_providers('Spatie\Permission\PermissionServiceProvider'),
        ];
        foreach($als as $a=>$b){
            if($b==false){$return[]=$a;}
        }
        return $return;
    }
}
