import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;
import java.security.*;
import java.util.Formatter;
import java.util.InputMismatchException;
import java.util.Scanner;

public class Main {
    private static final String HMAC_SHA_256 = "HmacSHA256";

    private static String toHexString(byte[] bytes) {
        Formatter formatter = new Formatter();
        for (byte b : bytes) {
            formatter.format("%02x", b);
        }
        return formatter.toString();
    }

    public static String calculateHMAC(String data, byte[] bytes)
            throws SignatureException, NoSuchAlgorithmException, InvalidKeyException
    {
        SecretKeySpec secretKeySpec = new SecretKeySpec(bytes, HMAC_SHA_256);
        Mac mac = Mac.getInstance(HMAC_SHA_256);
        mac.init(secretKeySpec);
        return toHexString(mac.doFinal(data.getBytes()));
    }


    public static void main(String[] args) throws NoSuchAlgorithmException, SignatureException, InvalidKeyException {
        int k=0;
        for(int i=0;i<args.length;i++){
            for (int j=i+1;j<args.length;j++){
                if(args[i].equals(args[j])){
                    k=1;
                    break;
                }
            }
        }
        if(args.length%2==0||args.length<3||k==1){
            System.out.println("ERROR");
        }
        else{
            int[][] resultTable=new int[args.length][args.length];
            for(int i=0;i<args.length;i++){
                for (int j=i;j<args.length;j++){
                    if(i!=j) {
                        if (j - i <= args.length / 2) {
                            resultTable[i][j] =-1;
                            resultTable[j][i]=1;
                        }
                        else{
                            resultTable[i][j]=1;
                            resultTable[j][i]=-1;
                        }
                    }
                }
            }
            byte[]key=new byte[32];
            SecureRandom secureRandom=new SecureRandom();
            secureRandom.nextBytes(key);
            int computerTurn=(int)(Math.random()*(args.length));
            System.out.println("HMAC: "+calculateHMAC(args[computerTurn],key));
            while(true){
                System.out.println("Choose your Turn:");
                for(int i=1;i<=args.length;i++){
                    System.out.println(i+" - "+args[i-1]);
                }
                System.out.println("0 - exit");
                try {
                    Scanner scanner = new Scanner(System.in);
                    int userTurn = scanner.nextInt();
                    if (userTurn - 1 < args.length) {
                        if (userTurn > 0) {
                            if (resultTable[userTurn - 1][computerTurn] == 0) {
                                System.out.println("draw");
                                System.out.println("Computer move is " + args[computerTurn]);
                                System.out.println("Your move is " + args[userTurn - 1]);
                                break;
                            } else if (resultTable[userTurn - 1][computerTurn] == 1) {
                                System.out.println("You win!");
                                System.out.println("Computer move is " + args[computerTurn]);
                                System.out.println("Your move is " + args[userTurn - 1]);
                                break;
                            } else {
                                System.out.println("You lose");
                                System.out.println("Computer move is " + args[computerTurn]);
                                System.out.println("Your move is " + args[userTurn - 1]);
                                break;
                            }
                        } else if (userTurn == 0) {
                            break;
                        } else {

                        }
                    }
                }
                catch (InputMismatchException e){

                }
            }
            System.out.print("HMAC key: ");
            for (byte b : key) {
                System.out.print(String.format("%02x",b));
            }
        }
    }
}
