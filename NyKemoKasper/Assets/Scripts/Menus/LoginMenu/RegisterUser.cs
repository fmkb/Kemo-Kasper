using System.Collections;
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
        // hiding all the warnings
        warningPasswordMatch.gameObject.SetActive(false);
        warningPasswordReq.gameObject.SetActive(false);
        warningEmail.gameObject.SetActive(false);
        warningFieldsEmpty.gameObject.SetActive(false);
    }

    public void EmptyFields()
    {
        // emptying all the fields
        emailField.text = "";
        passwordField.text = "";
        passwordRepeatField.text = "";
        nameField.text = "";
    }

    public void RegisterNewUser()
    {
        // checking if the right canvas is open
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

            // checking if there is no user using given email address yet

            // if every condition is met, then create an account
            if (isAllOk)
            {
                EmptyFields();
                this.GetComponent<LoginMenuNavigation>().SwitchToLoginTab();
                this.GetComponent<LoginMenuNavigation>().GoBackToMain();
                registerConfirmation.SetActive(true);
                StartCoroutine(Countdown());
                isAllOk = false;

                // sending data with the new account to the website here
            }
        }
    }

    private bool ArePasswordsTheSame()
    {
        // checking if passwords from both fields are the same
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
        // checking if the password requirement are met
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
        // checking if there are no empty fields
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
        // checking if entered email ís an email address
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
        // coroutine disabling the register confirmation after 3 seconds
        yield return new WaitForSeconds(3);
        registerConfirmation.SetActive(false);
    }
}
