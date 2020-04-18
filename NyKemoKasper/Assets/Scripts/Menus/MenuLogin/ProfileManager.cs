using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ProfileManager : MonoBehaviour
{
    public Button editNameButton;
    public Button acceptName;
    public Button declineName;

    public GameObject namePrompt;
    public GameObject warningTooLong;
    public InputField nameField;

    public Button changeGenderButton;

    public Text playerName;
    public Text genderText;

    private string gender;

    public GameObject boyGender;
    public GameObject girlGender;

    void Start()
    {
        editNameButton.onClick.AddListener(OpenNamePropmpt);
        acceptName.onClick.AddListener(SetName);
        declineName.onClick.AddListener(CloseNamePrompt);
        changeGenderButton.onClick.AddListener(SetGender);

        namePrompt.SetActive(false);
        warningTooLong.SetActive(false);

        if(PlayerPrefs.HasKey("Name"))
        {
            if(PlayerPrefs.GetString("Name") != "")
            playerName.text = PlayerPrefs.GetString("Name");
        }
        else
        {
            playerName.text = "Not_set";
        }

        SetGender();
    }
    
    void OpenNamePropmpt()
    {
        namePrompt.SetActive(true);
    }

    void SetName()
    {
        if(nameField.text.Length > 2 && nameField.text.Length < 11)
        {
            playerName.text = nameField.text;
            PlayerPrefs.SetString("Name", playerName.text);
            CloseNamePrompt();
        }
        else
        {
            warningTooLong.SetActive(true);
        }
    }

    void CloseNamePrompt()
    {
        namePrompt.SetActive(false);
        warningTooLong.SetActive(false);
    }

    void SetGender()
    {
        if (PlayerPrefs.HasKey("Gender"))
        {
            if(PlayerPrefs.GetString("Gender") == "Boy")
            {
                PlayerPrefs.SetString("Gender", "Girl");
                boyGender.SetActive(false);
                girlGender.SetActive(true);
            }
            else
            {
                PlayerPrefs.SetString("Gender", "Boy");
                boyGender.SetActive(true);
                girlGender.SetActive(false);
            }
        }
        else
        {
            PlayerPrefs.SetString("Gender", "Girl");
            boyGender.SetActive(false);
            girlGender.SetActive(true);
        }
    }
}
