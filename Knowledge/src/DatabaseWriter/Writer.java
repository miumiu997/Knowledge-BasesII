package DatabaseWriter;

import java.io.BufferedReader;
import java.io.FileReader;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.PreparedStatement;
import java.sql.ResultSet;


public class Writer {
	
	
	public static void writeInDatabase(String File, int operation) throws IOException, SQLException{
		BufferedReader bf = new BufferedReader(new FileReader(File));
		String sCadena;
	    // Definimos el driver y la url
	    String sDriver = "com.mysql.jdbc.Driver";
	    String sURL = "jdbc:mysql://localhost:3306/KNOWLEDGE";
	    PreparedStatement stmt;
		Connection con = null; ;
		try {
		    
		    Class.forName(sDriver).newInstance(); 
		    
		    con = DriverManager.getConnection(sURL,"root","cloudera");
		  
		} catch (SQLException ex) {
		    // handle any errors
		    System.out.println("SQLException: " + ex.getMessage());
		    System.out.println("SQLState: " + ex.getSQLState());
		    System.out.println("VendorError: " + ex.getErrorCode());
		} catch (InstantiationException e) {
			System.out.println("ERROR AQUII 1");
			e.printStackTrace();
		} catch (IllegalAccessException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (ClassNotFoundException e) {
			System.out.println("ERROR AQUII 2");
			e.printStackTrace();
		}
		
		if(operation == 1 ){
			/*
			 * 1. Por	cada	palabra	distinta,	¿Cuáles	sitios	web	la	contienen?
			 * palabra	URl
			 * */
			while ((sCadena = bf.readLine())!=null) {	
				String[] arregloElementos = sCadena.split("	");//El split lo hago con el caracter "tab"
				String palabra = arregloElementos[0];
				String url = arregloElementos[1];
					if(palabra.length() > 0 && palabra.length() <50 && url.length() <250){
					 try{
					 stmt = con.prepareStatement("INSERT INTO WordInURL(word,url)VALUES (?,?)");
					 stmt.setString(1,palabra);
					 stmt.setString(2,url);
					 stmt.executeUpdate();
					 }catch(Exception ex){
						 System.out.println("Me despiche");
					 }
					}
				}
			}
		
		else if(operation ==2){
			
			/*
			 * 2. Para	cada	palabra	distinta en	un	sitio	web,	¿Cuál	es	el	conteo	total	de	la	palabra?
			 * 
			 */
			while ((sCadena = bf.readLine())!=null) {	
				
			}
		}
		
		else{
			/*
			3. Para	cada	palabra	distinta en	general,	¿Cuál	es	el	conteo	total	de	la	palabra?
			 */
			while ((sCadena = bf.readLine())!=null) {	
				String[] arregloElementos = sCadena.split("	");//El split lo hago con el caracter "tab"
				String palabra = arregloElementos[0];
				String CantVeces = arregloElementos[1];

				stmt = con.prepareStatement("INSERT INTO CountWordTotal(word,count)VALUES (?,?)");
				stmt.setString(1,palabra);
				stmt.setString(2,CantVeces);
				stmt.executeUpdate();
				
			}
		}
	}
	public static void  main(String[] args){
		
		try {
			writeInDatabase("/home/cloudera/Desktop/part-r-00000",1);
		} catch (IOException | SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
