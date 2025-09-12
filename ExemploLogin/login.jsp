<%@page language="java" import="java.sql.*" %>
<%
    // Variaveis para cada campo digitado
    String vlogin = request.getParameter("loginUsuario");
    String vsenha = request.getParameter("loginSenha");

    // Variaveis para o banco de dados
    String banco    = "testelogin";
    String endereco = "jdbc:mysql://localhost:3306/" + banco;
    String usuario  = "root";
    String senha    = "";
  
    String driver = "com.mysql.jdbc.Driver"; //Variavel para o Driver
    Class.forName( driver ); //Carregar o driver na memória
    Connection conexao; //Cria a variavel para conectar com banco
    conexao = DriverManager.getConnection(endereco, usuario, senha); // Abrir a conexao com o banco

    //Cria a variavel sql como o comando SELECT
    String sql = "SELECT * FROM usuarios WHERE usuario=? AND senha=? ";

    //Cria a variavel do tipo Statement para executar o camando no banco
    PreparedStatement stm = conexao.prepareStatement(sql);
    stm.setString(1, vlogin);
    stm.setString(2, vsenha);

    ResultSet dados =  stm.executeQuery(); //Cria a variavel do tipo ResultSet para armazenar os dados que estão no banco

    if(dados.next())
    {
       session.setAttribute("usuariologado", dados.getString("nome")) ;
       response.sendRedirect("pagina2.html");
    }
    else
    {
        out.print("<script> alert('Login incorreto!'); window.location.href='home.html'; </script>");
    }
%>