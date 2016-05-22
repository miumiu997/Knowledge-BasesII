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
          ArrayList<String> separatedValues = splitfile(line);
          String word = separatedValues.get(1);
          String url = separatedValues.get(2);
          
          context.write(new Text(word), new Text(url));
          
          
     }
   
    public static ArrayList<String> splitfile (String line) {
    	ArrayList<String> spfile = new ArrayList<String>();
    	int lentag = line.indexOf("}");
    	String tag = line.substring(1, lentag);
    	spfile.add(tag);
    	
    	String substring2 = line.substring(lentag+1);
    	
    	int lenWord = substring2.indexOf("}");
    	String word = substring2.substring(1,lenWord);
    	spfile.add(word);
    	String substring3 = substring2.substring(lenWord);
    	String url = substring3.substring(2,substring3.length()-1);
    	spfile.add(url);
    	return spfile;
    	
    }
    


}
