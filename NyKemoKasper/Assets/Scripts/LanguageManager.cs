using UnityEngine;

public class LanguageManager : MonoBehaviour
{
    public string languageSet;

    void Start()
    {
        PlayerPrefs.SetString("Language", languageSet);
    }
}
