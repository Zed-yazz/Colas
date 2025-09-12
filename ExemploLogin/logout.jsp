<%
  if( session != null ){
    session.invalidate();
    response.sendRedirect("home.html");
  }
%>