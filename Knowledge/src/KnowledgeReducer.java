import java.io.*;
import org.apache.hadoop.io.Writable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Reducer;

public class KnowledgeReducer extends Reducer<Text, Writable, Text, Writable> {
	
	@Override
	public void reduce(Text key, Iterable<Writable> values, Context context )throws IOException, InterruptedException
	{
		for(Writable value:values){
		context.write(key,value);
		}
		
	}
}
