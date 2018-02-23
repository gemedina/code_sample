//
//  view.swift
//  FiveClub
//
//  Created by Gersen Medina Dias on 27/07/17.
//  Copyright © 2017 Ge Medina. All rights reserved.
//

import UIKit
import FBSDKLoginKit

class SettingsVC: UIViewController {

    //UI
    @IBOutlet weak var headerAvatar: UIImageView!
    @IBOutlet weak var userNameLbl: UILabel!
    @IBOutlet weak var checkinsCounterLbl: UILabel!
    var loading:MBProgressHUD! // Declara o loading

    override func viewDidLoad() {
        super.viewDidLoad()
        
        //Esconde o Top Bar
        navigationController?.setNavigationBarHidden(true, animated: true)
        
        // round corners
        headerAvatar.layer.cornerRadius = headerAvatar.bounds.width / 2
        headerAvatar.clipsToBounds = true
        
        //Coloca o loading onde precisa
        self.loading = MBProgressHUD.showAdded(to: self.view, animated: true)
        self.loading.label.text = ""

    }
    
    override func viewWillAppear(_ animated: Bool) {
        self.loadUserInfos()
        
        self.loadUserAvatar()
    }
    
    func loadUserAvatar() {
        
        user = UserDefaults.standard.value(forKey: "parseJSON") as? NSDictionary
        let userAvatar = user?["avatar"] ?? ""
        print(userAvatar)
        
        let imageURL = NSURL(string: (userAvatar as! String))! as URL
        // get user profile picture
        DispatchQueue.main.async(execute: {
            // get data from image url
            let imageData = NSData(contentsOf: imageURL)
            // if data is not nill assign it to ava.Img
            if imageData != nil {
                DispatchQueue.main.async(execute: {
                    self.headerAvatar.image = UIImage(data: imageData! as Data)
                })
            }
        })
    }
    
    
    // search / retrieve users
    func loadUserInfos() {
        
        // shortcut to id
        let id = user!["id"] as! String
        
        let url = NSURL(string: "FILE")!  // url path to users.php file
        let request = NSMutableURLRequest(url: url as URL) // create request to work with users.php file
        request.httpMethod = "POST" // method of passing inf to users.php
        let body = "id=\(id)" // body that passes inf to users.php
        request.httpBody = body.data(using: String.Encoding.utf8) // convert str to utf8 str - supports all languages
        
        // launch session
        URLSession.shared.dataTask(with: request as URLRequest, completionHandler: { (data:Data?, response:URLResponse?, error:Error?) in
            
            // getting main queue of proceeding inf to communicate back, in another way it will do it in background
            // and user will no see changes :)
            DispatchQueue.main.async(execute: {
                
                if error == nil {
                    
                    do {
                        
                        // getting content of $returnArray variable of php file
                        let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary

                        if let parseJSON = json {
                            if let JSON_LOCAIS  = parseJSON["posts"] as? [AnyObject]
                            {
                                for friendObj in JSON_LOCAIS
                                {
                                    
                                    let user_name = (friendObj["username"] as! String)
                                    let user_checkins_counter = (friendObj["checkinCounter"] as! String)
                                    self.userNameLbl.text! = user_name
                                    self.checkinsCounterLbl.text! = "Fez check-in \(user_checkins_counter) dia(s) 🏅"

                                    //Esconde o loading
                                    self.loading.hide(animated: true)
                                }
                            }
                        }
                    } catch {
                        // get main queue to communicate back to user
                        DispatchQueue.main.async(execute: {
                            let message = String(describing: error)
                            appDelegate.infoView(message: message, color: colorSmoothRed)
                        })
                        return
                    }
                } else {
                    // get main queue to communicate back to user
                    DispatchQueue.main.async(execute: {
                        let message = error!.localizedDescription
                        appDelegate.infoView(message: message, color: colorSmoothRed)
                    })
                    return
                }
            })
        }).resume()
    }
    

    @IBAction func deslogar_click(_ sender: Any) {
        
        //Removendo informacoes do app usando NSUserDetauls
        UserDefaults.standard.removeObject(forKey: "permissaoGeral");
        UserDefaults.standard.synchronize()
        //Removendo informacoes do app usando NSUserDetauls
        
        self.navigationController?.setNavigationBarHidden(false, animated: true)
        
        let accessToken = FBSDKAccessToken.current()
        
        if(accessToken != nil){
            
            // ******** DESLOGANDO O USUARIO
            let loginManager = FBSDKLoginManager()
            loginManager.logOut()

            // ******** JOGA O USUÁRIO PARA A TELA DE LOGIN
            let loginPage = self.storyboard?.instantiateViewController(withIdentifier: "ViewController") as! ViewController
            let loginPageNav = UINavigationController(rootViewController: loginPage)
            let appDelegate = UIApplication.shared.delegate as! AppDelegate
            appDelegate.window?.rootViewController = loginPageNav
            // ******** JOGA O USUÁRIO PARA A TELA DE LOGIN
            
        }
        
    }
    
 

}
