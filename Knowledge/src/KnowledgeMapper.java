import java.io.IOException;
import java.util.ArrayList;
import java.util.StringTokenizer;
import java.io.*;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.io.*;
import org.apache.hadoop.mapred.*;
import org.apache.hadoop.util.ToolRunner;
import org.apache.hadoop.mapreduce.Mapper;

public class KnowledgeMapper extends Mapper<Writable, Text, Text, Writable> {
	

    public void map(Writable key, Text value, Context context) throws IOException, InterruptedException
    {
    		
          String line = value.toString();
          System.out.println(line);
        //[0]=h1,[1]=palabra,[2]=url
          ArrayList<String> separatedValues = splitfile(line);
          if(separatedValues.size()>0){
	          String word = separatedValues.get(1);
	          String url = separatedValues.get(2);
	          context.write(new Text(word), new Text(url));
	       }
          
     }
   
    public static ArrayList<String> splitfile (String line) {
    	ArrayList<String> spfile = new ArrayList<String>();
    	int lentag = line.indexOf("}");
    	if(lentag>0){
    	String tag = line.substring(1, lentag);
    	spfile.add(tag);
    	}
    	int lenPart2= lentag+1;
    	String substring2 = "";
    	if(lenPart2 > 0){
    		 substring2 = line.substring( lenPart2);
    	}
    	int lenWord =0;
    	if(substring2.length() >0){
    		lenWord = substring2.indexOf("}");
    	}
    	String word ="";
    	if(lenWord>0){
    	word = substring2.substring(1,lenWord);
    	spfile.add(word);
    	}
    	
    	String substring3 = substring2.substring(lenWord);
    	int index = substring3.length()-1;
    	if(index > 0){
    		String url = substring3.substring(2,index);
    		spfile.add(url);
    	}
    	
    	return spfile;
    	
    }
    


}