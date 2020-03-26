using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class RegisterUser : MonoBehaviour
{
    public InputField emailField;
    public InputField passwordField;
    public InputField passwordRepeatField;
    public InputField nameField;

    [SerializeField]
    private LoginCredentials registerCredentials = new LoginCredentials();

    public GameObject warningPasswordMatch;
    public GameObject warningPasswordReq;
    public GameObject warningEmail;
    public GameObject warningFieldsEmpty;

    public Button registerButton;
    private bool isAllOk;
    public GameObject registerConfirmation;

    void Start()
    {
        RemoveWarnings();

        registerConfirmation.SetActive(false);

        EmptyFields();

        isAllOk = false;

        registerButton.onClick.AddListener(RegisterNewUser);
    }

    public void RemoveWarnings()
    {
        warningPasswordMatch.gameObject.SetActive(false);
        warningPasswordReq.gameObject.SetActive(false);
        warningEmail.gameObject.SetActive(false);
        warningFieldsEmpty.gameObject.SetActive(false);
    }

    public void EmptyFields()
    {
        emailField.text = "";
        passwordField.text = "";
        passwordRepeatField.text = "";
        nameField.text = "";
    }

    public void RegisterNewUser()
    {
        if (emailField.gameObject.activeInHierarchy)
        {
            RemoveWarnings();

            if (ArePasswordRequirementMet())
            {
                registerCredentials.Password = passwordField.text;
            }

            if (IsEmailCorrect())
            {
                registerCredentials.Email = emailField.text;
            }

            if (AreNoneFieldsEmpty())
            {
                isAllOk = true;
            }

            if (isAllOk)
            {
                EmptyFields();
                this.GetComponent<LoginMenuNavigation>().SwitchToLoginTab();
                this.GetComponent<LoginMenuNavigation>().GoBackToMain();
                registerConfirmation.SetActive(true);
                StartCoroutine(Countdown());
                isAllOk = false;
            }
        }
    }

    private bool ArePasswordsTheSame()
    {
        if (passwordField.text == passwordRepeatField.text)
        {
            warningPasswordMatch.SetActive(false);
            return true;
        }
        else
        {
            warningPasswordMatch.SetActive(true);
            passwordField.text = "";
            passwordRepeatField.text = "";
            return false;
        }
    }

    private bool ArePasswordRequirementMet()
    {
        if (passwordField.text.Length > 6)
        {
            warningPasswordReq.SetActive(false);
            if (ArePasswordsTheSame())
            {
                return true;
            }
            return true;
        }
        else
        {
            warningPasswordReq.SetActive(true);
            passwordField.text = "";
            passwordRepeatField.text = "";
            return false;
        }
    }

    private bool AreNoneFieldsEmpty()
    {
        if (emailField.text.Length > 0 && nameField.text.Length > 0)
        {
            warningFieldsEmpty.SetActive(false);
            return true;
        }
        else
        {
            warningFieldsEmpty.SetActive(true);
            passwordField.text = "";
            passwordRepeatField.text = "";
            return false;
        }
    }

    private bool IsEmailCorrect()
    {
        if (emailField.text.Contains("@"))
        {
            warningEmail.SetActive(false);
            return true;
        }
        else
        {
            warningEmail.SetActive(true);
            passwordField.text = "";
            passwordRepeatField.text = "";
            return false;
        }
    }

    private IEnumerator Countdown()
    {
        yield return new WaitForSeconds(3);
        registerConfirmation.SetActive(false);
    }
}
