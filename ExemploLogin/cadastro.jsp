<%@page language="java" import="java.sql.*" %>

<%
    //variaveis para cada campo digitado,
    String vnome  = request.getParameter("cadNome");
    String vuser  = request.getParameter("cadUsuario");
    String vsenha = request.getParameter("cadSenha");


    //variaveis para o banco de dados
    String banco    = "testelogin";
    String endereco = "jdbc:mysql://localhost:3306/"+banco;
    String usuario  = "root";
    String senha    = "";

    String driver = "com.mysql.jdbc.Driver"; //Variavel para o Driver
    Class.forName(driver); //Carregar o driver na memória
    Connection conexao; //Variavel para conectar com o banco de dados
    conexao = DriverManager.getConnection(endereco, usuario, senha); //Abrir a conexao com o banco de dados

    //Cria a variavel sql como o comando INSERT
    String sql = "INSERT INTO usuarios (nome, usuario, senha) values (?,?,?)";

    PreparedStatement stm = conexao.prepareStatement(sql); //cria a variavel do tipo Statement
    stm.setString(1, vnome);
    stm.setString(2, vuser);
    stm.setString(3, vsenha);

    stm.execute();
    stm.close();

    out.print("CADASTRO REALIZADO COM SUCESSO!!!");
    out.print("<br><br>");
    out.print("<a href='home.html'>Voltar</a>");

%>