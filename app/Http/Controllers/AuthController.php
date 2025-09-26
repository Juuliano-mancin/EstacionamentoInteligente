<?php

namespace App\Http\Controllers; /* Define o namespace do controlador; ajuda o Laravel a organizar e localizar este controller dentro da estrutura do projeto. */
use Illuminate\Http\Request; /* Importa a classe Request, usada para capturar e manipular dados das requisições HTTP. */
use Illuminate\Support\Facades\Auth; /* Importa a facade Auth, responsável por lidar com autenticação */

class AuthController extends Controller
{
    public function showLoginForm() /* Método responsável por exibir o formulário de login. Esse método retorna a view 'auth.login' */
        {
            return view('auth.login');
        }

    public function login(Request $request)  /* método que recebe a requisição de login com os dados do formulário */
        {           
            $credentials = $request->validate /* valida os dados enviados pelo formulário e armazena em $credentials */ 
                ([
                    'email' => ['required', 'email'], /* campo obrigatório e deve ser um e-mail válido */   
                    'password' => ['required'], /* campo obrigatório */       
                ]);
           
                if (Auth::attempt($credentials)) /* tenta autenticar o usuário com as credenciais fornecidas */
                    {
                        $request->session()->regenerate(); /* regenera a sessão para segurança após login */
                        return redirect()->intended('dashboard'); /* redireciona para o dashboard ou rota que o usuário tentou acessar */
                    }
           
                return back()->withErrors /* retorna para a página anterior com mensagens de erro */
                    (['email' => 'As credenciais fornecidas não correspondem aos nossos registros.', ])->onlyInput('email'); /* exibe erro no campo email e mantém o valor preenchido */
        }

    public function logout(Request $request) /* método que encerra a sessão do usuário e realiza o logout */
        {
            Auth::logout(); /* desloga o usuário */
            $request->session()->invalidate(); /* invalida a sessão atual para segurança */
            $request->session()->regenerateToken(); /* gera um novo token CSRF */
            return redirect()->route('login'); /* redireciona para a página de login */
        }
}
