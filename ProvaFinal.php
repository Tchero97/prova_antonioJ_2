<?php

//primeira classe

class Produto {
    private $codigo;
    private $nome;
    private $marca;
    private $preco;
    private $estoque;

    public function __construct($codigo, $nome, $marca, $preco, $estoque) {
        $this->codigo = $codigo;
        $this->nome = $nome;
        $this->marca = $marca;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function mostrarDetalhesProduto() {
        echo "O produto {$this->nome} da marca {$this->marca} custa: {$this->preco} reais. <br>";
    }

    public function getNome() {
        return $this->nome;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getEstoque() {
        return $this->estoque;
    }

    public function removerEstoque($quantidade) {
        if ($this->estoque >= $quantidade) {
            $this->estoque -= $quantidade;
        } else {
            echo "Estoque insuficiente para o produto {$this->nome}.<br>";
        }
    }

    public function adicionarEstoque($quantidade) {
        $this->estoque += $quantidade;
    }
}

//segunda classe

class Cliente {
    private $nome;
    private $cpf;
    private $endereco;
    private $telefone;
    private $historicoCompras;


    public function __construct($nome, $cpf, $endereco, $telefone) {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->endereco = $endereco;
        $this->telefone = $telefone;
        $this->historicoCompras = array();
    }

    public function adicionarCompra($compra) {
        $this->historicoCompras[] = $compra;
        echo "Compra adicionada ao histórico do Sr(a): {$this->nome}. <br>";
    }

    public function getHistoricoCompras() {
        echo "Histórico de compras Sr(a): {$this->nome}: <br>";
        foreach ($this->historicoCompras as $compra) {
            foreach ($compra as $item) {
                $produto = $item['produto'];
                $quantidade = $item['quantidade'];
                echo "- Produto: {$produto->getNome()}, Quantidade: {$quantidade}, Preço: {$produto->getPreco()}<br>";
            }
        }
        return $this->historicoCompras;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }
}

//terceira classe

class CarrinhoDeCompras {
    private $cliente;
    private $itens;
    private $valorTotal;

    public function __construct($cliente) {
        $this->cliente = $cliente;
        $this->itens = array();
        $this->valorTotal = 0.0;
    }

    public function adicionarItem($produto, $quantidade) {
        $this->itens[] = array('produto' => $produto, 'quantidade' => $quantidade);
        $this->valorTotal += $produto->getPreco() * $quantidade;
    }

    public function removerItem($produto) {
        foreach ($this->itens as $key => $item) {
            if ($item['produto'] === $produto) {
                unset($this->itens[$key]);
                $this->valorTotal -= $item['produto']->getPreco() * $item['quantidade'];
                break;
            }
        }
    }

    public function finalizarCompra() {
        foreach ($this->itens as $item) {
            $item['produto']->removerEstoque($item['quantidade']);
        }
        $this->cliente->adicionarCompra($this->itens);
        return $this->valorTotal; 
    }

    public function getValorTotal() {
        return $this->valorTotal;
    }

    public function getItens() {
        return $this->itens;
    }
}

//quarta classe

class Caixa {
    private $fila;
    private $clienteAtual;
    private $produtos;
    private $valorTotalVendas;

    public function __construct() {
        $this->fila = array();
        $this->clienteAtual = null;
        $this->produtos = array();
        $this->valorTotalVendas = 0.0;
    }

    public function atenderCliente(Cliente $cliente, array $produtos) {
        $this->clienteAtual = $cliente;
        $this->produtos = $produtos;
        $this->processarPagamento();
    }

    private function processarPagamento() {
        if ($this->clienteAtual && !empty($this->produtos)) {
            $carrinho = new CarrinhoDeCompras($this->clienteAtual);

            foreach ($this->produtos as $item) {
                $produto = $item['produto'];
                $quantidade = $item['quantidade'];
                $carrinho->adicionarItem($produto, $quantidade);
            }

            $valorTotal = $carrinho->finalizarCompra();
            $this->valorTotalVendas += $valorTotal;

            
            echo "Compra finalizada para o cliente {$this->clienteAtual->getNome()}.<br>";
            echo "Produtos comprados:<br>";
            foreach ($carrinho->getItens() as $item) {
                $produto = $item['produto'];
                $quantidade = $item['quantidade'];
                echo "- Produto: {$produto->getNome()}, Quantidade: {$quantidade}<br>";
            }
            echo "Dados do cliente:<br>";
            echo "- Nome: {$this->clienteAtual->getNome()}<br>";
            echo "- CPF: {$this->clienteAtual->getCpf()}<br>";
            echo "- Endereço: {$this->clienteAtual->getEndereco()}<br>";
            echo "- Telefone: {$this->clienteAtual->getTelefone()}<br>";
            echo "Valor total: {$valorTotal} reais.<br>";
            echo "Histórico de compras:<br>";
            $this->clienteAtual->getHistoricoCompras();
        } else {
            echo "Não há cliente ou produtos para processamento.<br>";
        }
    }

    public function mostrarValorTotalVendas() {
        echo "Valor total das vendas: {$this->valorTotalVendas} reais.<br>";
    }
}

//quinta classe

class ValorTotalDeVendas {
    private $listaProdutos;
    private $valorTotalVendas;
    private $quantidadeTotalProdutos;

    public function __construct() {
        $this->listaProdutos = array();
        $this->valorTotalVendas = 0.0;
        $this->quantidadeTotalProdutos = 0;
    }

    public function cadastrarProduto($produto) {
        $this->listaProdutos[] = $produto;
    }

    public function atualizarEstoque($produto, $novaQuantidade) {
        foreach ($this->listaProdutos as $p) {
            if ($p === $produto) {
                $p->adicionarEstoque($novaQuantidade);
                break;
            }
        }
    }

    public function calcularValorTotalVendas() {
        $valorTotal = 0.0;
        foreach ($this->listaProdutos as $produto) {
            $valorTotal += $produto->getPreco() * ($produto->getEstoque() - $this->quantidadeTotalProdutos);
        }
        return $valorTotal;
    }

    public function calcularQuantidadeTotalProdutos() {
        $quantidadeTotal = 0;
        foreach ($this->listaProdutos as $produto) {
            $quantidadeTotal += $produto->getEstoque();
        }
        return $quantidadeTotal;
    }

    public function mostrarValorTotalVendas() {
        echo "Valor total das vendas: {$this->calcularValorTotalVendas()} reais.<br>";
    }

    public function mostrarQuantidadeTotalProdutos() {
        echo "Quantidade total de produtos: {$this->calcularQuantidadeTotalProdutos()}<br>";
    }
}

print_r ('<br>');

$produto1 = new Produto(1, "Arroz", "São João", 12.99, 5);
$produto2 = new Produto(2, "Feijão", "São Batista", 10.99, 44);
$produto3 = new Produto(3, "Carne", "Sadia", 40.89, 33);
$produto4 = new Produto(4, "Batata", "Terra", 8.99, 22);
$produto5 = new Produto(5, "Refrigerante", "Cola-Cola", 11.99, 10);

$cliente = new Cliente("Daenerys Targaryen", "545.656.989-99", "Rua Do Dragão, 666", "(49) 9-5656-7878");


$caixa = new Caixa();
$caixa->atenderCliente($cliente, array(
    array('produto' => $produto1, 'quantidade' => 2),
    array('produto' => $produto2, 'quantidade' => 3),
    array('produto' => $produto3, 'quantidade' => 5),
    array('produto' => $produto4, 'quantidade' => 10),
    array('produto' => $produto5, 'quantidade' => 1)
));

print_r ('<br>');


$caixa->mostrarValorTotalVendas();

print_r ('<br>');

$gerenciamento = new ValorTotalDeVendas();
$gerenciamento->cadastrarProduto($produto1);
$gerenciamento->cadastrarProduto($produto2);
$gerenciamento->cadastrarProduto($produto3);
$gerenciamento->cadastrarProduto($produto4);
$gerenciamento->cadastrarProduto($produto5);

print_r ('<br>');

$gerenciamento->mostrarValorTotalVendas();
$gerenciamento->mostrarQuantidadeTotalProdutos();


?>
