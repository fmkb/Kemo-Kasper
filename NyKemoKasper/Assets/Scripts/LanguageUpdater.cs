using UnityEngine;

public class LanguageUpdater : MonoBehaviour
{
    public GameObject[] textParents;
    private string language;

    void Start()
    {
        language = PlayerPrefs.GetString("Language");

        DisableAllTexts();

        switch(language)
        {
            case "Danish":
                SetLanguage(0);
                break;
            case "English":
                SetLanguage(1);
                break;
            case "German":
                SetLanguage(2);
                break;
            default:
                SetLanguage(0);
                break;
        }
    }
    
    void DisableAllTexts()
    {
        foreach(GameObject text in textParents)
        {
            text.transform.GetChild(0).gameObject.SetActive(false);
            text.transform.GetChild(1).gameObject.SetActive(false);
            text.transform.GetChild(2).gameObject.SetActive(false);
        }
    }

    void SetLanguage(int languageIndex)
    {
        foreach(GameObject text in textParents)
        {
            text.transform.GetChild(languageIndex).gameObject.SetActive(true);
        }
    }
}
