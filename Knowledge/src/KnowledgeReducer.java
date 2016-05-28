import java.io.*;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.hadoop.io.Writable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Reducer;

public class KnowledgeReducer extends Reducer<Text, Writable, Text, Writable> {
	
	@Override
	public void reduce(Text key, Iterable<Writable> values, Context context )throws IOException, InterruptedException
	{	
		/*
		//Punto 1
		ArrayList<String> valuesList = new ArrayList<String>();
		for(Writable value:values){
			if(!valuesList.contains(value.toString())){
				valuesList.add(value.toString());
				context.write(key,value);
			}
		}
		*/
		
		
		 
		 //<{llave}{url},5>
		
		//Punto 2
		
		Map<String,Integer> diccionario  = new HashMap<String,Integer>();
		for(Writable value:values){
			String newValue = "{"+key.toString()+"}"+"{"+value.toString()+"}";
			
			if(!diccionario.containsKey(newValue)){
				diccionario.put(newValue, 1);
			}else{
				int cantidad =diccionario.get(newValue);
				diccionario.put(newValue,cantidad+1);
			}
		}
		
		for (Entry<String, Integer> entry : diccionario.entrySet()) {
			String llave = entry.getKey(); 
			String valor = entry.getValue().toString();
			context.write(new Text(llave), new Text(valor));
			
			}
		 
		 
		
		/*
		///Punto 3
		int cantidadDeVecesGeneral =0;
		for(Writable value:values){
			cantidadDeVecesGeneral++;
		}
		context.write(key, new Text(Integer.toString(cantidadDeVecesGeneral)));
		*/
		
		
		
		}
		
		
	}


/*
 * 
 * 
 * 1. Por	cada	palabra	distinta,	¿Cuáles	sitios	web	la	contienen?
2. Para	cada	palabra	distinta en	un	sitio	web,	¿Cuál	es	el	conteo	total	de	la	palabra?
3. Para	cada	palabra	distinta en	general,	¿Cuál	es	el	conteo	total	de	la	palabra? R/ values.length()
 * */
