<?php

namespace App\Http\Controllers; /* define o namespace do controller, organizando-o dentro da estrutura da aplicação */

use Illuminate\Http\Request; /* importa a classe Request para manipular dados de requisições HTTP */
use App\Models\Estacionamento; /* importa o model Estacionamento para acessar a tabela correspondente */
use App\Models\Projeto; /* importa o model Projeto para acessar dados de projetos */
use App\Models\Setor; /* importa o model Setor para trabalhar com setores */
use App\Models\SetorGrid; /* importa o model SetorGrid para manipular grids de setores */
use Illuminate\Support\Facades\DB; /* importa a facade DB para executar queries diretas no banco */
use Illuminate\Support\Facades\Log; /* importa a facade Log para registrar logs da aplicação */

class EstacionamentoController extends Controller /* controller responsável por gerenciar estacionamentos, herda funcionalidades da classe base Controller */
    { 
        public function index() /* método que lista os estacionamentos ou exibe a página inicial relacionada */
            {
                $estacionamentos = Estacionamento::with('projeto')->get(); /* busca todos os estacionamentos junto com o relacionamento 'projeto' */
                return view('listarestacionamentos', compact('estacionamentos')); /* retorna a view 'listarestacionamentos' passando os dados dos estacionamentos */
            }

        public function create() /* método que exibe o formulário para criar um novo estacionamento */
            {
                $projetos = Projeto::all(); /* busca todos os projetos disponíveis no banco */
                return view('novoestacionamento', compact('projetos')); /* retorna a view 'novoestacionamento' passando os projetos para o formulário */
            }

        public function store(Request $request) /* método que recebe os dados do formulário e salva um novo estacionamento no banco */
            {
                $request->validate /* valida os dados enviados pelo formulário antes de salvar no banco */
                    ([
                        'idProjeto' => 'required|exists:tb_projeto,idProjeto', /* obrigatório e deve existir na tabela tb_projeto */
                        'gridsEixoX' => 'required|integer|min:1', /* obrigatório, inteiro, valor mínimo 1 */
                        'gridsEixoY' => 'required|integer|min:1', /* obrigatório, inteiro, valor mínimo 1 */
                        'vagasCarro' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasMoto' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasPreferencial' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasPcd' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                    ]);

                Estacionamento::create($request->all()); /* cria um novo registro de estacionamento no banco com todos os dados validados do formulário */

                return redirect()->back()->with('success', 'Estacionamento cadastrado com sucesso!'); /* redireciona de volta para o formulário com uma mensagem de sucesso */
            }

        public function show(Estacionamento $estacionamento) /* método que exibe os detalhes de um estacionamento específico */
            {
                $estacionamento->load('projeto'); /* carrega o relacionamento 'projeto' do estacionamento */
                return view('mostrarestacionamento', compact('estacionamento')); /* retorna a view 'mostrarestacionamento' passando os dados do estacionamento */
            }

        public function edit(Estacionamento $estacionamento) /* método que exibe o formulário para editar um estacionamento existente */
            {
                $projetos = Projeto::all(); /* busca todos os projetos disponíveis para selecionar no formulário de edição */
                return view('editarestacionamento', compact('estacionamento', 'projetos')); /* retorna a view de edição com os dados do estacionamento e a lista de projetos */
            }

        public function update(Request $request, Estacionamento $estacionamento) /* método que recebe os dados do formulário e atualiza um estacionamento existente */
            {
                $request->validate /* valida os dados enviados pelo formulário antes de atualizar o estacionamento */
                    ([
                        'idProjeto' => 'required|exists:tb_projeto,idProjeto', /* obrigatório e deve existir na tabela tb_projeto */
                        'gridsEixoX' => 'required|integer|min:1', /* obrigatório, inteiro, valor mínimo 1 */
                        'gridsEixoY' => 'required|integer|min:1', /* obrigatório, inteiro, valor mínimo 1 */
                        'vagasCarro' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasMoto' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasPreferencial' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                        'vagasPcd' => 'required|integer|min:0', /* obrigatório, inteiro, valor mínimo 0 */
                    ]);

                $estacionamento->update($request->all()); /* atualiza o registro do estacionamento com os dados validados do formulário */

                return redirect()->route('estacionamentos.index')->with('success', 'Estacionamento atualizado com sucesso!'); /* redireciona para a lista de estacionamentos com uma mensagem de sucesso */
            }

        public function destroy(Estacionamento $estacionamento) /* método que exclui um estacionamento específico do banco */
            {
                $estacionamento->delete(); /* remove o registro do estacionamento do banco de dados */
                return redirect()->route('estacionamentos.index')->with('success', 'Estacionamento excluído com sucesso!'); /* redireciona para a lista de estacionamentos com mensagem de sucesso */
            }


        public function salvarSetores(Request $request) /* método que recebe os dados do formulário e salva os setores de um estacionamento */
            {
                try { 
                    Log::info('salvarSetores chamado', $request->all()); /* registra um log de informação indicando que o método salvarSetores foi chamado, incluindo todos os dados da requisição */

                    $data = $request->validate([ /* valida os dados enviados pelo formulário antes de processar o salvamento dos setores */
                        'idEstacionamento' => 'required|integer|exists:tb_estacionamento,idEstacionamento', /* obrigatório, inteiro e deve existir na tabela de estacionamentos */
                        'setores' => 'required|array', /* obrigatório e deve ser um array de setores */
                        'setores.*.nomeSetor' => 'required|string', /* obrigatório, deve ser string, nome de cada setor */
                        'setores.*.corSetor' => 'nullable|string', /* opcional, deve ser string, cor de cada setor */
                        'setores.*.grids' => 'required|array', /* obrigatório, array contendo as posições de cada setor */
                        'setores.*.grids.*.x' => 'required|integer', /* obrigatório, coordenada X de cada grid */
                        'setores.*.grids.*.y' => 'required|integer', /* obrigatório, coordenada Y de cada grid */
                    ]);

                    DB::beginTransaction(); /* inicia uma transação no banco de dados para garantir que todas as operações seguintes sejam atômicas */

                    $estacionamento = Estacionamento::find($data['idEstacionamento']); /* busca o estacionamento correspondente ao id fornecido nos dados validados */
                    if (!$estacionamento) /* verifica se o estacionamento não foi encontrado */
                    {
                        return response()->json(['success'=>false,'message'=>'Estacionamento não encontrado'], 404); /* retorna uma resposta JSON informando que o estacionamento não foi encontrado, com status HTTP 404 */
                    }

                    $estacionamento->setores()->delete(); /* exclui todos os setores relacionados ao estacionamento antes de salvar os novos */

                    foreach ($data['setores'] as $setorData)  /* percorre cada setor enviado nos dados do formulário para processar e salvar individualmente */
                    {
                        $setor = Setor::create([ /* cria um novo setor no banco e atribui o objeto criado à variável $setor */
                            'idEstacionamento' => $data['idEstacionamento'], /* define o ID do estacionamento ao qual o setor pertence */
                            'nomeSetor' => $setorData['nomeSetor'], /* define o nome do setor com base nos dados enviados */
                            'corSetor' => $setorData['corSetor'] ?? null, /* define a cor do setor, ou null se não fornecida */
                        ]);

                        $grids = []; /* inicializa um array para armazenar os grids do setor */
                        foreach ($setorData['grids'] as $grid) /* percorre cada grid enviado para o setor */
                        {
                            $grids[] = [ /* adiciona um novo item ao array de grids */
                                'idSetor' => $setor->idSetor, /* associa o grid ao setor criado */
                                'posX' => $grid['x'], /* define a coordenada X do grid */
                                'posY' => $grid['y'], /* define a coordenada Y do grid */
                                'created_at' => now(), /* define a data de criação do registro */
                                'updated_at' => now(), /* define a data de atualização do registro */
                            ];
                        }

                        if (count($grids) > 0) /* verifica se existem grids para inserir antes de executar a operação de banco */
                        {
                            SetorGrid::insert($grids); /* insere todos os grids do setor de uma vez na tabela SetorGrid */
                        }
                    }

                    DB::commit(); /* confirma a transação no banco de dados */
                    return response()->json(['success'=>true,'message'=>'Setores salvos com sucesso']); /* retorna sucesso em JSON */

                } catch (\Illuminate\Validation\ValidationException $e) { /* captura erros de validação */
                    return response()->json(['success'=>false,'errors'=>$e->errors()], 422); /* retorna os erros de validação com status 422 */
                } catch (\Exception $e) { /* captura qualquer outra exceção */
                    DB::rollBack(); /* desfaz a transação em caso de erro */
                    Log::error('Erro ao salvar setores: '.$e->getMessage()); /* registra o erro no log */
                    return response()->json(['success'=>false,'message'=>'Erro ao salvar setores: '.$e->getMessage()], 500); /* retorna mensagem de erro com status 500 */
                }
            }


        
        public function getEstacionamento($idProjeto, $idEstacionamento) /* método que retorna os detalhes de um estacionamento específico baseado no projeto e ID */
            {
                $estacionamento = Estacionamento::with('projeto') /* carrega o estacionamento junto com o relacionamento 'projeto' */
                    ->where('idProjeto', $idProjeto) /* filtra pelo ID do projeto */
                    ->where('idEstacionamento', $idEstacionamento) /* filtra pelo ID do estacionamento */
                    ->first(); /* pega o primeiro registro que corresponde aos filtros */

                if(!$estacionamento) { /* verifica se nenhum estacionamento foi encontrado */
                    return response()->json(['error' => 'Estacionamento não encontrado'], 404); /* retorna erro 404 em JSON */
                }

                return response()->json([ /* retorna os dados do estacionamento em formato JSON */
                    'idProjeto' => $estacionamento->idProjeto, /* ID do projeto */
                    'idEstacionamento' => $estacionamento->idEstacionamento, /* ID do estacionamento */
                    'gridsEixoX' => $estacionamento->gridsEixoX, /* número de grids no eixo X */
                    'gridsEixoY' => $estacionamento->gridsEixoY, /* número de grids no eixo Y */
                    'vagasCarro' => $estacionamento->vagasCarro, /* quantidade de vagas para carro */
                    'vagasMoto' => $estacionamento->vagasMoto, /* quantidade de vagas para moto */
                    'vagasPreferencial' => $estacionamento->vagasPreferencial, /* quantidade de vagas preferenciais */
                    'vagasPcd' => $estacionamento->vagasPcd, /* quantidade de vagas para PCD */
                    'nomeProjeto' => $estacionamento->projeto->nomeProjeto ?? null /* nome do projeto, ou null se não existir */
                ]);
            }


        public function vagasEstacionamento($idEstacionamento) /* método que exibe a página com todas as vagas de um estacionamento específico */
        {
            $estacionamento = Estacionamento::with(['projeto', 'setores.grids'])->findOrFail($idEstacionamento); /* busca o estacionamento pelo ID, carregando projeto, setores e grids, ou retorna erro 404 se não encontrado */
            return view('vagasestacionamento', compact('estacionamento')); /* retorna a view 'vagasestacionamento' passando os dados do estacionamento */
        }
    }